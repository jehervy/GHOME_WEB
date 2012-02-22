<?php

namespace GHOME\CoreBundle\Formatter;

class StringFormatter extends Formatter
{
	public function format($value)
	{
		return str_replace('{{ value }}', $value, $this->options['format']);
	}
	
	public function getCssClass($value)
	{
	    return '';
	}
	
	public function isBoolean()
	{
	    return false;
	}
	
	protected function getDefaultOptions()
	{
		return array('format' => '{{ value }}');
	}
}