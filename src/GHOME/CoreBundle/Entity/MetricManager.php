<?php

namespace GHOME\CoreBundle\Entity;

class MetricManager
{
	private $dir;
	private $metrics = array();
	private $formatters = array(
		'boolean' => '\GHOME\CoreBundle\Formatter\BooleanFormatter',
		'string' => '\GHOME\CoreBundle\Formatter\StringFormatter',
		'nullOrString' => '\GHOME\CoreBundle\Formatter\NullOrStringFormatter',
	);
	private $validators = array(
		'boolean' => '\GHOME\CoreBundle\Validator\BooleanValidator',
		'nullOrInteger' => '\GHOME\CoreBundle\Validator\nullOrIntegerValidator',
	);
	
	public function __construct($dir)
	{
		$this->dir = $dir;
		
		$this->initialize();
	}
	
	public function findAll()
	{
		return $this->metrics;
	}
	
	public function find($index)
	{
		return isset($this->metrics[$index]) ? $this->metrics[$index] : null;
	}
	
	private function initialize()
	{
		$home = new \SimpleXMLElement(file_get_contents($this->dir.'/metrics.xml'));
		
		foreach ($home->metric as $metric)
		{
			$id = (int) $metric['id'];
			$name = (string) $metric->name;
			$formatters = array();
			
			if (isset($metric->sensor))
			{
				$formatters[0] = $this->getFormatter($metric->sensor->formatter);
			}
			if (isset($metric->actuator))
			{
				$formatters[1] = $this->getFormatter($metric->actuator->formatter);
				$formatters[2] = $this->getValidator($metric->actuator->validator);
			}
			
			$this->metrics[$id] = new Metric($id, $name, $formatters);
		}
	}
		
	private function getFormatter($node)
	{
		$type = (string) $node['type'];
		
		if (!isset($this->formatters[$type]))
		{
			throw new \InvalidArgumentException('Formatter '.$type.' not found.');
		}
		
		$class = $this->formatters[$type];
		$options = array();
		
		foreach ($node->attributes() as $key => $value)
		{
			if ($key !== 'type')
			{
				$options[$key] = (string) $value;
			}
		}
		
		return new $class($options);
	}
	
	private function getValidator($node)
	{
		$type = (string) $node['type'];
		
		if (!isset($this->validators[$type]))
		{
			throw new \InvalidArgumentException('Validator '.$type.' not found.');
		}
		
		$class = $this->validators[$type];
		$options = array();
		
		foreach ($node->attributes() as $key => $value)
		{
			if ($key !== 'type')
			{
				$options[$key] = (string) $value;
			}
		}
		
		return new $class($options);
	}
}