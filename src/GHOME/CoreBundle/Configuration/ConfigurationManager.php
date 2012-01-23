<?php

namespace GHOME\CoreBundle\Configuration;

class ConfigurationManager
{
	private $dir;
	private $rooms;
	private $metrics;
	private $actuators;
	private $rules;
	private $sensors;
	
	public function __construct($dir)
	{
		$this->dir = $dir;
	}
	
	public function getRooms()
	{
		$this->initializeRooms();
		
		return $this->rooms;
	}
	
	public function getRoom($index)
	{
		$this->initializeRooms();
		
		return $this->rooms[$index];
	}
	
	public function getMetrics()
	{
		$this->initializeMetrics();
		
		return $this->metrics;
	}
	
	public function getSensors()
	{
		$this->initializeSensors();
		
		return $this->sensors;
	}
	
	private function initializeMetrics()
	{
		if (!isset($this->metrics))
		{
			$this->metrics = array();
			$home = new \SimpleXMLElement(file_get_contents($this->dir.'/home.xml'));
			foreach ($home->metrics->metric as $metric)
			{
				$id = (int) $metric['id'];
				$options = array();
				foreach ($metric->format->attributes() as $key => $value)
				{
					if ($key !== 'type')
					{
						$options[$key] = $value;
					}
				}
				$formatter = new MetricFormatter((string) $metric->format['type'], $options);
				$this->metrics[$id] = array('name' => (string) $metric->name, 'formatter' => $formatter);
			}
		}
	}
	
	private function initializeRooms()
	{
		if (!isset($this->rooms))
		{
			$this->rooms = array();
			$home = new \SimpleXMLElement(file_get_contents($this->dir.'/home.xml'));
			foreach ($home->rooms->room as $room)
			{
				$id = (int) $room['id'];
				$this->rooms[$id] = array('name' => (string) $room->name);
			}
		}
	}
	
	private function initializeSensors()
	{
		if (!isset($this->sensors))
		{
			$this->sensors = array();
			$home = new \SimpleXMLElement(file_get_contents($this->dir.'/sensors.xml'));
			foreach ($home->sensors->sensor as $sensor)
			{
				$id = (int) $sensor['id'];
				$this->sensors[$id] = array('metric' => (int) $sensor->metric, 'room' => (int) $sensor->room);
			}
		}
	}
}