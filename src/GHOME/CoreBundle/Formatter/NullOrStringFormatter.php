<?php

namespace GHOME\CoreBundle\Formatter;

/**
 * Mix of string and boolean formatters. If value is false, displays on string.
 * In all other cases displays value in a preformatter string.
 */
class NullOrStringFormatter extends Formatter
{
    /**
	 * {@inheritdoc}
	 */
	public function format($value)
	{
		return (boolean) $value ? str_replace('{{ value }}', $value, $this->options['format']) : $this->options['no'];
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getCssClass($value)
	{
	    return (boolean) $value ? 'label-success' : 'label-important';
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function isBoolean()
	{
	    return false;
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getDefaultOptions()
	{
		return array('no' => 'Inactif', 'format' => '{{ value }}');
	}
}