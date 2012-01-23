<?php

namespace GHOME\CoreBundle\Configuration;

class MetricFormatter
{
	private $type;
	private $options;
	
	public function __construct($type, array $options)
	{
		$this->type = $type;
		$this->options = $options;
	}
	
	public function format($value)
	{
		if ($this->type === 'value')
		{
			return str_replace('{{ value }}', $value, $this->options['display']);
		}
		elseif ($this->type === 'boolean')
		{
			return (int) $value ? $this->options['yes'] : $this->options['no'];
		}
		
		return 'Unknown type '.$this->type;
	}
}