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
		
		$xml = '<?xml version="1.0"?>'."\n".'<rules>'."\n";
		foreach ($this->entities as $rule)
		{
		    $xml .= '<rule>'."\n".'<if>'."\n";
		    foreach ($rule->getConditions() as $condition)
		    {
		        $xml .= '<metric id="'.$condition->getMetric()->getId().'" cond="'.$condition->getComparator().'">'.$condition->getThreshold().'</metric>'."\n";
		    }
		    $xml .= '</if>'."\n"."<then>"."\n";
		    foreach ($rule->getActions() as $action)
		    {
		        $xml .= '<metric id="'.$action->getMetric()->getId().'">'.$action->getValue().'</metric>'."\n";
		    }
		    $xml .= '</then>'."\n".'</rule>'."\n";
		}
		$xml .= '</rules>';
		
		echo $xml;
		//file_put_contents($this->dir.'/rules.xml', $xml);
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
}