<?php

namespace GHOME\CoreBundle\Entity;

class RuleManager
{
	private $dir;
	private $metricManager;
	private $entities = array();
	
	public function __construct($dir, $metricManager)
	{
		$this->dir = $dir;
		$this->metricManager = $metricManager;

		$this->initialize();
	}
	
	public function findAll()
	{
		return $this->entities;
	}
	
	public function find($index)
	{
		return isset($this->entities[$index]) ? $this->entities[$index] : null;
	}
	
	public function add(Rule $rule, $position = null)
	{
	    $rule->canonicalize();
	    
		if (!isset($position))
		{
			$this->entities[] = $rule;
		}
		else
		{
			for ($i = count($this->entities) - 1; $i >= $position; $i--)
			{
				$this->entities[$i + 1] = $this->entities[$i];
			}
			$this->entities[$position] = $rule;
		}
		
		$this->writeXml();
	}
	
	public function remove($position)
	{
	    unset($this->entities[$position]);
	    $this->writeXml();
	}
	
	private function initialize()
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
	
	private function writeXml()
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
}