<?php

namespace GHOME\CoreBundle\Formatter;

class BooleanFormatter extends Formatter
{
	public function format($value)
	{
		return (int) $value ? $this->options['yes'] : $this->options['no'];
	}
	
	protected function getDefaultOptions()
	{
		return array('yes' => 'Actif', 'no' => 'Inactif');
	}
}