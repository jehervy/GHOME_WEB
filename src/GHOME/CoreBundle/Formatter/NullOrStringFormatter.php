<?php

namespace GHOME\CoreBundle\Formatter;

class NullOrStringFormatter extends Formatter
{
	public function format($value)
	{
		return (int) $value ? str_replace('{{ value }}', $value, $this->options['format']) : $this->options['no'];
	}
	
	protected function getDefaultOptions()
	{
		return array('no' => 'Inactif', 'format' => '{{ value }}');
	}
}