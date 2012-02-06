<?php

namespace GHOME\CoreBundle\Entity;

class Room
{
    protected $id;
    protected $name;

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