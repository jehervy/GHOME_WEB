<?php

namespace GHOME\CoreBundle\Entity;

/**
 * Manages the physical view of the EnOcean sensors.
 */
class EnOceanSensorManager extends Manager
{
	private $address;
	private $port;
	
	/**
	 * {@inheritdoc}
	 */
	public function add($entity, $position = null)
	{
	    if (!$entity instanceof EnOceanSensor)
	    {
	        throw new \InvalidArgumentException(sprintf(
	            '$entity must be an instance of EnOceanSensor (got instance of "%s")',
	            get_class($entity)
	        ));
	    }
	    
	    if (!$entity->getVirtualId())
	    {
	        $entity->setVirtualId(count($this->entities) + 1);
	    }
	    
	    parent::add($entity, $position);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function flush()
	{
	    $xml = '<?xml version="1.0"?>'."\n".'<sensors address="'.$this->address.'" port="'.$this->port.'">'."\n";
		foreach ($this->entities as $sensor)
		{
		    $xml .= "\t".'<sensor>'."\n";
		    $xml .= "\t\t".'<virtualId>'.$sensor->getVirtualId().'</virtualId>'."\n";
		    $xml .= "\t\t".'<physicalId>'.$sensor->getPhysicalId().'</physicalId>'."\n";
		    $xml .= "\t\t".'<valid>'.$sensor->getValid().'</valid>'."\n";
		    $xml .= "\t\t".'<data type="'.$sensor->getDataType().'" pos="'.$sensor->getDataPos().'" length="'.$sensor->getDataLength().'">'."\n";
		    if ($sensor->getDataType() === 'numeric')
		    {
		        $xml .= "\t\t\t".'<min>'.$sensor->getDataMin().'</min>'."\n";
		        $xml .= "\t\t\t".'<max>'.$sensor->getDataMax().'</max>'."\n";
	        }
	        else if ($sensor->getDataType() === 'binary')
		    {
		        foreach ($sensor->getDataValues() as $binary => $real)
		        {
		            $xml .= "\t\t\t".'<value data="'.$binary.'">'.$real.'</value>'."\n";
	            }
	        }
	        $xml .= "\t\t".'</data>'."\n";
	        $xml .= "\t".'</sensor>'."\n";
		}
		$xml .= '</sensors>';
		
		file_put_contents($this->dir.'/enOceanSensorsId.xml', $xml);
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function initialize()
	{
		$sensors = new \SimpleXMLElement(file_get_contents($this->dir.'/enOceanSensorsId.xml'));
		
		$this->address = (string) $sensors['address'];
		$this->port = (int) $sensors['port'];
		
		foreach ($sensors->sensor as $sensor)
		{
			$s = new EnOceanSensor();
			
			$s->setVirtualId((int) $sensor->virtualId);
			$s->setPhysicalId((string) $sensor->physicalId);
			$s->setValid((int) $sensor->valid);
			$s->setDataType((string) $sensor->data['type']);
			$s->setDataPos((int) $sensor->data['pos']);
			$s->setDataLength((int) $sensor->data['length']);
			
			if ($s->getDataType() === 'numeric')
			{
			    $s->setDataMin((int) ((string) $sensor->data->min));
    			$s->setDataMax((int) ((string) $sensor->data->max));
			}
			elseif ($s->getDataType() === 'binary')
			{
			    foreach ($sensor->data as $data)
    			{
    			    $s->addDataValue((int) $data['data'], (int) ((string) $data));
    			}
			}
			
			$this->entities[$s->getVirtualId()] = $s;
		}
	}
}