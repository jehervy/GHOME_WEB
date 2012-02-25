<?php

namespace GHOME\CoreBundle\Entity;

/**
 * Manages the rules of the inference engine.
 */
class RuleManager extends Manager
{
	private $metricManager;
	
	/**
	 * {@inheritdoc}
	 */
	public function __construct($dir, $metricManager)
	{
		$this->metricManager = $metricManager;		
		parent::__construct($dir);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function add($entity, $position = null)
	{
	    if (!$entity instanceof Rule)
	    {
	        throw new \InvalidArgumentException(sprintf(
	            '$entity must be an instance of Rule (got instance of "%s")',
	            get_class($entity)
	        ));
	    }
	    
	    $entity->canonicalize();
	    parent::add($entity, $position);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function flush()
	{
	    $xml = '<?xml version="1.0"?>'."\n".'<rules>'."\n";
		foreach ($this->entities as $rule)
		{
		    $xml .= "\t".'<rule>'."\n\t\t".'<if>'."\n";
		    foreach ($rule->getConditions() as $condition)
		    {
		        $xml .= "\t\t\t".'<metric id="'.$condition->getMetric()->getId().'" cond="'.$condition->getComparator().'">'.$condition->getThreshold().'</metric>'."\n";
		    }
		    $xml .= "\t\t".'</if>'."\n\t\t"."<then>"."\n";
		    foreach ($rule->getActions() as $action)
		    {
		        $xml .= "\t\t\t".'<metric id="'.$action->getMetric()->getId().'">'.$action->getValue().'</metric>'."\n";
		    }
		    $xml .= "\t\t".'</then>'."\n\t".'</rule>'."\n";
		}
		$xml .= '</rules>';
		
		file_put_contents($this->dir.'/rules.xml', $xml);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function initialize()
	{
		$rules = new \SimpleXMLElement(file_get_contents($this->dir.'/rules.xml'));
		
		foreach ($rules->rule as $rule)
		{
			$ruleEntity = new Rule();
			foreach ($rule->if->metric as $metric)
			{
				$id = (int) $metric['id'];
				$ruleEntity->addCondition($this->metricManager->find($id), (string) $metric['cond'], (string) $metric);
			}
			foreach ($rule->then->metric as $metric)
			{
				$id = (int) $metric['id'];
				$ruleEntity->addAction($this->metricManager->find($id), (string) $metric);
			}
			
			$this->entities[] = $ruleEntity;
		}
	}
}