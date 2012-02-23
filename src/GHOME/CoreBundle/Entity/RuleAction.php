<?php 

namespace GHOME\CoreBundle\Entity;

/**
 * An action that will be done when all conditions match in the rule.
 */
class RuleAction
{
	protected $metric;
	protected $value;

    /**
     * Constructor.
     *
     * @param Metric $metric The metric to move
     * @param integer $value The value to be taken
     */
	public function __construct(Metric $metric, $value)
	{
		$this->metric = $metric;
		$this->value = $value;
	}
	
	/**
	 * Returns the metric associated with the action.
	 *
	 * @return Metric
	 */
	public function getMetric()
	{
		return $this->metric;
	}
	
	/**
	 * Returns the value to be taken by the metric.
	 *
	 * @return integer
	 */
	public function getValue()
	{
		return $this->value;
	}
	
	/**
	 * Checks if two actions can be merged.
	 *
	 * @param RuleAction $action The action to compare
	 * @return boolean
	 */
	public function equals(RuleAction $action)
	{
	    return ($action->getMetric()->getId() == $this->metric->getId());
	}
	
	/**
	 * Merges two rules with the max strategy into the current action.
	 *
	 * @param RuleAction $action The action to merge
	 */
	public function merge(RuleAction $action)
	{
	    $this->value = max($this->value, $action->getValue());
	}
}