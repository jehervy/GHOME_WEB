<?php

namespace GHOME\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ActionRepository
 */
class ActionRepository extends EntityRepository
{
    /**
     * Finds last values of actuators for each metric and room combination.
     *
     * @return Collection
     */
    public function findLastValues()
	{
	    return $this->getEntityManager()
	        ->createQuery(
				'SELECT a FROM GHOMECoreBundle:Action a '.
				'WHERE a.time = (SELECT MAX(b.time) FROM GHOMECoreBundle:Action b WHERE b.metric = a.metric AND b.room = a.room) '.
				'GROUP BY a.metric, a.room '.
				'ORDER BY a.metric')
	        ->getResult();
	}
	
	/**
	 * Finds all actions for a specific metric and room.
	 *
	 * @param integer $metric The identifier of the metric
	 * @param integer $room The identifier of the room
	 * @return Collection
	 */
	public function findByMetricAndRoom($metric, $room)
	{
	    return $this->getEntityManager()
	        ->createQuery(
				'SELECT a FROM GHOMECoreBundle:Action a '.
				'WHERE a.metric = :metric AND a.room = :room '.
				'ORDER BY a.time DESC')
			->setParameter('metric', $metric)
			->setParameter('room', $room)
	        ->getResult();
	}
}