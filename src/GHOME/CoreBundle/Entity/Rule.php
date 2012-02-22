<?php 

namespace GHOME\CoreBundle\Entity;

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
	
	public function getConditions()
	{
		return $this->conditions;
	}
	
	public function getActions()
	{
		return $this->actions;
	}
	
	public function addCondition($metric, $comparator, $threshold)
	{
		$this->conditions[] = new RuleCondition($metric, $comparator, $threshold);
	}
	
	public function setConditions($conditions)
	{
	    $this->conditions = $conditions;
	}
	
	public function addAction($metric, $value)
	{
		$this->actions[] = new RuleAction($metric, $value);
	}
	
	public function setActions($actions)
	{
	    $this->actions = $actions;
	}
	
	public function formatComparator($comparator)
	{
		return isset($this->comparators[$comparator]) ? $this->comparators[$comparator] : $comparator;
	}
	
	public function canonicalize()
	{
	    $this->actions = $this->doCanonicalize($this->actions);
	    $this->conditions = $this->doCanonicalize($this->conditions);
	}
	
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