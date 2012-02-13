<?php 

namespace GHOME\CoreBundle\Entity;

class RuleCondition
{
	protected $metric;
	protected $comparator;
	protected $threshold;
	
	private static $comparators = array(
		'sup' => '>',
		'inf' => '<',
		'supeq' => '≥',
		'infeq' => '≤',
		'eq' => '=',
	);
	
	public function __construct(Metric $metric = null, $comparator = null, $threshold = null)
	{
		$this->metric = $metric;
		$this->comparator = $comparator;
		$this->threshold = $threshold;
	}
	
	public static function getComparators()
	{
		return self::$comparators;
	}
	
	public function setComparator($comparator)
	{
		$this->comparator = $comparator;
	}
	
	public function getComparator()
	{
		return $this->comparator;
	}
	
	public function setMetric($metric)
	{
		$this->metric = $metric;
	}
	
	public function getMetric()
	{
		return $this->metric;
	}
	
	public function setThreshold($threshold)
	{
		$this->threshold = $threshold;
	}
	
	public function getThreshold()
	{
		return $this->threshold;
	}
	
	public function formatComparator()
	{
		return isset($this->comparators[$this->comparator]) ? $this->comparators[$this->comparator] : $this->comparator;
	}
}