<?php

namespace GHOME\CoreBundle\Formatter;

class StringFormatter extends Formatter
{
	public function format($value)
	{
		return str_replace('{{ value }}', $value, $this->options['format']);
	}
	
	protected function getDefaultOptions()
	{
		return array('format' => '{{ value }}');
	}
}