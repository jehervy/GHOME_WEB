<?php

namespace GHOME\CoreBundle\Formatter;

class NullOrStringFormatter extends Formatter
{
	public function format($value)
	{
		return (boolean) $value ? str_replace('{{ value }}', $value, $this->options['format']) : $this->options['no'];
	}
	
	public function getCssClass($value)
	{
	    return (boolean) $value ? 'label-success' : 'label-important';
	}
	
	protected function getDefaultOptions()
	{
		return array('no' => 'Inactif', 'format' => '{{ value }}');
	}
}