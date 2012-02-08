<?php

namespace GHOME\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="GHOME\CoreBundle\Repository\InfoRepository")
 * @ORM\Table(name="sensors_values")
 */
class Info
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

	/**
     * @ORM\Column(type="datetime")
     */
    protected $date;

    /**
     * @ORM\Column(type="integer")
     */
    protected $metric;

	/**
     * @ORM\Column(type="integer")
     */
    protected $room;

    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    protected $value;

	protected $roomEntity;
	protected $metricEntity;

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
     * Set date
     *
     * @param datetime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * Get date
     *
     * @return datetime 
     */
    public function getDate()
    {
        return $this->date;
    }

	/**
     * Get date as a timestamp
     *
     * @return integer
     */
    public function getTimestamp()
    {
        return strtotime($this->date->format('Y-m-d H:i:s'));
    }

    /**
     * Set metric
     *
     * @param integer $metric
     */
    public function setMetric($metric)
    {
     	$this->metric = $metric;
    }

    /**
     * Get metric
     *
     * @return integer 
     */
    public function getMetric()
    {
        return $this->metric;
    }

    /**
     * Set room
     *
     * @param integer $room
     */
    public function setRoom($room)
    {
        $this->room = $room;
    }

    /**
     * Get room
     *
     * @return integer 
     */
    public function getRoom()
    {
        return $this->room;
    }

	/**
     * Set metricEntity
     *
     * @param integer $metric
     */
    public function setMetricEntity($metric)
    {
     	$this->metricEntity = $metric;
    }

    /**
     * Get metricEntity
     *
     * @return integer 
     */
    public function getMetricEntity()
    {
        return $this->metricEntity;
    }

    /**
     * Set roomEntity
     *
     * @param integer $room
     */
    public function setRoomEntity($room)
    {
        $this->roomEntity = $room;
    }

    /**
     * Get roomEntity
     *
     * @return integer 
     */
    public function getRoomEntity()
    {
        return $this->roomEntity;
    }


    /**
     * Set value
     *
     * @param decimal $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Get value
     *
     * @return decimal 
     */
    public function getValue()
    {
        return $this->value;
    }
}