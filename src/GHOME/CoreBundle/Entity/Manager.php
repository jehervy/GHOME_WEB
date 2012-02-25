<?php

namespace GHOME\CoreBundle\Entity;

/**
 * Basic implementation of ManagerInterface.
 */
abstract class Manager implements ManagerInterface
{
    protected $dir;
	protected $entities = array();
	
	/**
	 * Constructor.
	 *
	 * @param string $dir Directory where the configuration files are.
	 */
	public function __construct($dir)
	{
		$this->dir = $dir;		
		$this->initialize();
	}
	
	/**
	 * {@inheritdoc}
	 */
    public function findAll()
	{
		return $this->entities;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function find($index)
	{
		return isset($this->entities[$index]) ? $this->entities[$index] : null;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function add($entity, $position = null)
	{
		if (!isset($position))
		{
			$this->entities[] = $entity;
		}
		else
		{
			for ($i = count($this->entities) - 1; $i >= $position; $i--)
			{
				$this->entities[$i + 1] = $this->entities[$i];
			}
			$this->entities[$position] = $entity;
		}
		
		$this->flush();
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function remove($index)
	{
	    unset($this->entities[$index]);
	    $this->flush();
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function flush()
    {
    }
	
	/**
	 * Populates the local entities.
	 */
	abstract protected function initialize();
}