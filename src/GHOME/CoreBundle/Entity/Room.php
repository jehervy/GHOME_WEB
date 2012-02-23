<?php

namespace GHOME\CoreBundle\Entity;

/**
 * A room where sensors and actuators can be contained.
 */
class Room
{
    protected $id;
    protected $name;

    /**
     * Constructor.
     *
     * @param integer $id The identifier
     * @param string $name The human-readable name
     */
	public function __construct($id, $name)
	{
		$this->id = $id;
		$this->name = $name;
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
}