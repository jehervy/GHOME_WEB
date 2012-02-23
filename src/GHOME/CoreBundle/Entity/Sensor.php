<?php

namespace GHOME\CoreBundle\Entity;

/**
 * A sensor contained in a room and periodically taking measures.
 */
class Sensor
{
    protected $id;
    protected $metric;
	protected $room;

    /**
     * Constructor.
     *
     * @param integer $id The identifier
     * @param Metric $metric The metric to be measured
     * @param Room $room The room where the sensor is
     */
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