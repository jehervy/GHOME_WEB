<?php

namespace GHOME\CoreBundle\Entity;

class Metric
{
    protected $id;
    protected $name;
	protected $formatters;

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

	public function isSensor()
	{
		return isset($this->formatters[0]);
	}
	
	public function isActuator()
	{
		return isset($this->formatters[1]);
	}

	public function formatSensor($value)
	{
		return $this->isSensor() ? $this->formatters[0]->format($value) : '';
	}
	
	public function formatActuator($value)
	{
		return $this->isActuator() ? $this->formatters[1]->format($value) : '';
	}
}