<?php

namespace GHOME\CoreBundle\Entity;

/**
 * Manages the logical view of the sensors.
 */
class SensorManager extends Manager
{
	protected $metricManager;
	protected $roomManager;
	
	/**
	 * {@inheritdoc}
	 */
	public function __construct($dir, $metricManager, $roomManager)
	{
		$this->metricManager = $metricManager;
		$this->roomManager = $roomManager;
		parent::__construct($dir);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function add($entity, $position = null)
	{
	    if (!$entity instanceof Sensor)
	    {
	        throw new \InvalidArgumentException(sprintf(
	            '$entity must be an instance of Sensor (got instance of "%s")',
	            get_class($entity)
	        ));
	    }
	    
	    parent::add($entity, $position);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function flush()
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
	
	/**
	 * {@inheritdoc}
	 */
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
}