<?php 

namespace GHOME\CoreBundle\Entity;

/**
 * A rule to be injected in the inference engine.
 */
class Rule
{
	protected $conditions = array();
	protected $actions = array();
	
	private $comparators = array(
		'sup' => '>',
		'inf' => '<',
		'supeq' => '≥',
		'infeq' => '≤',
		'eq' => '=',
	);
	
	/**
	 * Return all conditions of the rule.
	 *
	 * @return array A list of RuleCondition
	 */
	public function getConditions()
	{
		return $this->conditions;
	}
	
	/**
	 * Return all actions done when conditions are true.
	 *
	 * @return array A list of RuleAction
	 */
	public function getActions()
	{
		return $this->actions;
	}
	
	/**
	 * Adds a new condition.
	 *
	 * @param Metric $metric The metric concerned by the condition
	 * @param string $comparator The comparator
	 * @param integer $threshold The threshold to be compared with the metric
	 */
	public function addCondition(Metric $metric, $comparator, $threshold)
	{
		$this->conditions[] = new RuleCondition($metric, $comparator, $threshold);
	}
	
	/**
	 * Adds a new action.
	 *
	 * @param Metric $metric The metric concerned by the action
	 * @param integer $value The value to be taken by the metric
	 */
	public function addAction(Metric $metric, $value)
	{
		$this->actions[] = new RuleAction($metric, $value);
	}
	
	/**
	 * Returns a comparator in his human-readable form.
	 *
	 * @param  string $comparator The comparator to format
	 * @return string
	 */
	public function formatComparator($comparator)
	{
		return isset($this->comparators[$comparator]) ? $this->comparators[$comparator] : $comparator;
	}
	
	/**
	 * Canonicalize this rule: finds duplicate conditions or actions 
	 * and merge them.
	 */
	public function canonicalize()
	{
	    $this->conditions = $this->doCanonicalize($this->conditions);
	    $this->actions = $this->doCanonicalize($this->actions);
	}
	
	/**
	 * Checks if two elements are equal and merge them if this is true.
	 *
	 * @param  array $elements Elements to canonicalize
	 * @return array
	 */
	public function doCanonicalize(array $elements)
	{
	    $canonicalized = array();
	    foreach ($elements as $element)
	    {
	        $found = false;
	        foreach ($canonicalized as $elem)
	        {
	            if ($elem->equals($element))
	            {
	                $elem->merge($element);
	                $found = true;
	            }
	        }
	        if (!$found)
	        {
	            $canonicalized[] = $element;
	        }
	    }
	    
	    return $canonicalized;
	}
}