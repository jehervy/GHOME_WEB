<?php

namespace GHOME\CoreBundle\Entity;

/**
 * Interface for all classes managing entites not handled by Doctrine but 
 * stored in a custom data source (XML files for example).
 */
interface ManagerInterface
{
    /**
     * Returns all managed entities.
     *
     * @return array
     */
	function findAll();
	
	/**
	 * Returns a managed entity by its index.
	 *
	 * @param integer $index
	 * @return object
	 */
	function find($index);
	
	/**
	 * Adds a new entity to manage.
	 *
	 * @param object $entity
	 * @param integer|null $position The position where the entity must by 
	 *                               inserted, null for the tail.
	 */
	function add($entity, $position = null);
	
	/**
	 * Removes the managed entity at the given index.
	 *
	 * @param integer $index
	 */
	function remove($index);
	
	/**
	 * Persists all modifications in the data source.
	 */
	function flush();
}