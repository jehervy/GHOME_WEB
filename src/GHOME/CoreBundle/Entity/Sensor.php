<?php

namespace GHOME\CoreBundle\Entity;

class Sensor
{
    protected $id;
    protected $metric;
	protected $room;

	public function __construct($id, Metric $metric, Room $room)
	{
		$this->id = $id;
		$this->metric = $metric;
		$this->room = $room;
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
     * Get metric
     *
     * @return Metric
     */
    public function getMetric()
    {
        return $this->metric;
    }

	/**
     * Get room
     *
     * @return Room
     */
    public function getRoom()
    {
        return $this->room;
    }
}