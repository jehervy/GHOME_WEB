<?php 

namespace GHOME\CoreBundle\Entity;

/**
 * A condition to be matched in a rule in order to trigger the actions.
 */
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
	
	/**
	 * Constructor.
	 *
	 * @param Metric $metric
	 * @param string $comparator
	 * @param integer $threshold
	 */
	public function __construct(Metric $metric, $comparator, $threshold)
	{
		$this->metric = $metric;
		$this->comparator = $comparator;
		$this->threshold = $threshold;
	}
	
	/**
	 * Returns all available comparators with the name as key and the 
	 * human-readable label as value.
	 *
	 * @return array
	 */
	public static function getComparators()
	{
		return self::$comparators;
	}
	
	/**
	 * Returns the comparator associated with the condition.
	 *
	 * @return string
	 */
	public function getComparator()
	{
		return $this->comparator;
	}
	
	/**
	 * Returns the metric associated with the condition.
	 *
	 * @return Metric
	 */
	public function getMetric()
	{
		return $this->metric;
	}
	
	/**
	 * Returns the threshold associated with the condition.
	 *
	 * @return integer
	 */
	public function getThreshold()
	{
		return $this->threshold;
	}
	
	/**
	 * Returns the human-readable version of the comparator associated 
	 * with the current condition.
	 *
	 * @return string
	 */
	public function formatComparator()
	{
		return isset($this->comparators[$this->comparator]) ? $this->comparators[$this->comparator] : $this->comparator;
	}
	
	/**
	 * Checks if two conditions can be merged.
	 *
	 * @param RuleCondition $condition The condition to compare
	 * @return boolean
	 */
	public function equals(RuleCondition $condition)
	{
	    return (
	        $condition->getMetric()->getId() == $this->metric->getId()
	        && $condition->getComparator() == $this->comparator
	    );
	}
	
	/**
	 * Merges two conditions with the max strategy into the current condition.
	 *
	 * @param RuleCondition $condition The action to merge
	 */
	public function merge(RuleCondition $condition)
	{
	    if (!$this->equals($condition))
	    {
	        return;
	    }
	    
	    $this->threshold = max($this->threshold, $condition->getThreshold());
	}
}