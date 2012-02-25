<?php

namespace GHOME\CoreBundle\Entity;

/**
 * Represents one "thing" that will be measured. The concept is fuzzy enough 
 * in order to be able to deal with a large number of things.
 */
class Metric
{
    protected $id;
    protected $name;
	protected $formatters;

    /**
     * Constructor.
     *
     * @param integer $id The identifier of the metric
     * @param string $name The human-readable name of the metric
     * @param array $formatters The formatters to use (first for sensor, second for actuator)
     */
	public function __construct($id, $name, array $formatters)
	{
		$this->id = $id;
		$this->name = $name;
		$this->formatters = $formatters;
	}

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Does the metric work as sensor?
     *
     * @return boolean
     */
	public function isSensor()
	{
		return isset($this->formatters[0]);
	}
	
	/**
     * Does the metric work as actuator?
     *
     * @return boolean
     */
	public function isActuator()
	{
		return isset($this->formatters[1]);
	}
	
	/**
     * Does the sensor side work with booleans?
     *
     * @return boolean
     */
	public function isSensorBoolean()
	{
	    return $this->isSensor() && $this->formatters[0]->isBoolean();
	}
	
	/**
     * Does the actuator side work with booleans?
     *
     * @return boolean
     */
	public function isActuatorBoolean()
	{
	    return true;
	}

    /**
     * Formats a value using the sensor formatter.
     *
     * @param mixed $value
     * @return string
     */
	public function formatSensor($value)
	{
		return $this->isSensor() ? $this->formatters[0]->format($value) : '';
	}
	
	/**
     * Formats a value using the actuator formatter.
     *
     * @param mixed $value
     * @return string
     */
	public function formatActuator($value)
	{
		return $this->isActuator() ? $this->formatters[1]->format($value) : '';
	}
	
	/**
     * Returns the CSS class to use with the sensor formatter.
     *
     * @param mixed $value
     * @return string
     */
	public function getSensorCssClass($value)
    {
    	return $this->isSensor() ? $this->formatters[0]->getCssClass($value) : '';
    }
    
    /**
     * Returns the CSS class to use with the actuator formatter.
     *
     * @param mixed $value
     * @return string
     */
    public function getActuatorCssClass($value)
    {
    	return $this->isActuator() ? $this->formatters[1]->getCssClass($value) : '';
    }
}