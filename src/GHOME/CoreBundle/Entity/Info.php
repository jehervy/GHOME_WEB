<?php

namespace GHOME\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Represents a measure done by a sensor.
 *
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
    protected $time;

    /**
     * @ORM\Column(type="integer")
     */
    protected $metric;

	/**
     * @ORM\Column(type="integer")
     */
    protected $room;

    /**
     * @ORM\Column(type="integer")
     */
    protected $value;

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

    /**
     * Set time
     *
     * @param datetime $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * Get time
     *
     * @return datetime 
     */
    public function getTime()
    {
        return $this->time;
    }

	/**
     * Get date as a timestamp
     *
     * @return integer
     */
    public function getTimestamp()
    {
        return strtotime($this->time->format('Y-m-d H:i:s'));
    }
}