<?php

namespace GHOME\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="GHOME\CoreBundle\Repository\ActionRepository")
 * @ORM\Table(name="action")
 */
class Action
{
	const AUTHOR_SYSTEM = 'system';
	
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
     * @ORM\Column(type="string", length=100)
     */
    protected $author;

	/**
     * @ORM\Column(type="integer")
     */
    protected $room;

	/**
     * @ORM\Column(type="integer")
     */
    protected $metric;

    /**
     * @ORM\Column(type="decimal", scale=2)
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
     * Set author
     *
     * @param string $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * Get author
     *
     * @return string 
     */
    public function getAuthor()
    {
        return $this->author;
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