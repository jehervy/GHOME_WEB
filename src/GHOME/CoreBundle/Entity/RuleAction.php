<?php 

namespace GHOME\CoreBundle\Entity;

class RuleAction
{
	protected $metric;
	protected $value;

	public function __construct(Metric $metric = null, $value = null)
	{
		$this->metric = $metric;
		$this->value = $value;
	}
	
	public function setMetric($metric)
	{
		$this->metric = $metric;
	}
	
	public function getMetric()
	{
		return $this->metric;
	}
	
	public function setValue($value)
	{
		$this->value = $value;
	}
	
	public function getValue()
	{
		return $this->value;
	}
}