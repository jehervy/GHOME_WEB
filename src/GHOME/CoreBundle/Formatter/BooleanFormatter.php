<?php

namespace GHOME\CoreBundle\Formatter;

/**
 * Handles two strings: one if the value is true, one other is it is false.
 */
class BooleanFormatter extends Formatter
{
    /**
	 * {@inheritdoc}
	 */
	public function format($value)
	{
		return (int) $value ? $this->options['yes'] : $this->options['no'];
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getCssClass($value)
	{
	    return (int) $value ? 'label-success' : 'label-important';
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function isBoolean()
	{
	    return true;
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function getDefaultOptions()
	{
		return array('yes' => 'Actif', 'no' => 'Inactif');
	}
}