<?php

namespace GHOME\CoreBundle\Entity;

class SensorManager
{
	protected $dir;
	protected $metricManager;
	protected $roomManager;
	protected $entities = array();
	
	public function __construct($dir, $metricManager, $roomManager)
	{
		$this->dir = $dir;
		$this->metricManager = $metricManager;
		$this->roomManager = $roomManager;
		
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
	
	public function add(Sensor $sensor, $position = null)
	{
		if (!isset($position))
		{
			$this->entities[] = $sensor;
		}
		else
		{
			for ($i = count($this->entities) - 1; $i >= $position; $i--)
			{
				$this->entities[$i + 1] = $this->entities[$i];
			}
			$this->entities[$position] = $sensor;
		}
		
		$this->writeXml();
	}
	
	public function remove($position)
	{
	    unset($this->entities[$position]);
	    $this->writeXml();
	}
	
	protected function initialize()
	{
		$sensors = new \SimpleXMLElement(file_get_contents($this->dir.'/sensors.xml'));
		
		foreach ($sensors->sensor as $sensor)
		{
			$id = (int) $sensor['id'];
			$metric = $this->metricManager->find((int) $sensor->metric);
			$room = $this->roomManager->find((int) $sensor->room);
			
			$this->entities[$id] = new Sensor($id, $metric, $room);
		}
	}
	
	private function writeXml()
	{
	    $xml = '<?xml version="1.0"?>'."\n".'<sensors>'."\n";
		foreach ($this->entities as $sensor)
		{
		    $xml .= "\t".'<sensor id="'.$sensor->getId().'">'."\n";
		    $xml .= "\t\t".'<metric>'.$sensor->getMetric()->getId().'</metric>'."\n";
		    $xml .= "\t\t".'<room>'.$sensor->getRoom()->getId().'</room>'."\n";
		    $xml .= "\t".'</sensor>'."\n";
	    }
		$xml .= '</sensors>';
		
		file_put_contents($this->dir.'/sensors.xml', $xml);
	}
}