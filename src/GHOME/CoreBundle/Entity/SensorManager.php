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
	
	protected function initialize()
	{
		$home = new \SimpleXMLElement(file_get_contents($this->dir.'/sensors.xml'));
		
		foreach ($home->sensors->sensor as $sensor)
		{
			$id = (int) $sensor['id'];
			$metric = $this->metricManager->find((int) $sensor->metric);
			$room = $this->roomManager->find((int) $sensor->room);
			
			$this->entities[$id] = new Sensor($id, $metric, $room);
		}
	}
}