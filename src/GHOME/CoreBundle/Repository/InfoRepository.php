<?php

namespace GHOME\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * InfoRepository
 */
class InfoRepository extends EntityRepository
{
    /**
     * Finds last values of sensors for each metric and room combination.
     *
     * @return Collection
     */
	public function findLastValues()
	{
	    return $this->getEntityManager()
	        ->createQuery(
				'SELECT i FROM GHOMECoreBundle:Info i '.
				'WHERE i.time = (SELECT MAX(j.time) FROM GHOMECoreBundle:Info j WHERE j.metric = i.metric AND j.room = i.room) '.
				'GROUP BY i.metric, i.room '.
				'ORDER BY i.metric')
	        ->getResult();
	}
	
	/**
	 * Finds all measures for a specific metric and room.
	 *
	 * @param integer $metric The identifier of the metric
	 * @param integer $room The identifier of the room
	 * @return Collection
	 */
	public function findByMetricAndRoom($metric, $room)
	{
	    return $this->getEntityManager()
	        ->createQuery(
				'SELECT i FROM GHOMECoreBundle:Info i '.
				'WHERE i.metric = :metric AND i.room = :room '.
				'ORDER BY i.time DESC')
			->setParameter('metric', $metric)
			->setParameter('room', $room)
	        ->getResult();
	}
}