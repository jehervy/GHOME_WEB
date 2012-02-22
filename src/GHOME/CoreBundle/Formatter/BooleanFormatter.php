<?php

namespace GHOME\CoreBundle\Formatter;

class BooleanFormatter extends Formatter
{
	public function format($value)
	{
		return (int) $value ? $this->options['yes'] : $this->options['no'];
	}
	
	public function getCssClass($value)
	{
	    return (int) $value ? 'label-success' : 'label-important';
	}
	
	public function isBoolean()
	{
	    return true;
	}
	
	protected function getDefaultOptions()
	{
		return array('yes' => 'Actif', 'no' => 'Inactif');
	}
}