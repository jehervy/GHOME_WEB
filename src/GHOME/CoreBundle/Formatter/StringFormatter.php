<?php

namespace GHOME\CoreBundle\Formatter;

/**
 * Integrates values in a preformatted string.
 */
class StringFormatter extends Formatter
{
    /**
	 * {@inheritdoc}
	 */
	public function format($value)
	{
		return str_replace('{{ value }}', $value, $this->options['format']);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getCssClass($value)
	{
	    return '';
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
		return array('format' => '{{ value }}');
	}
}