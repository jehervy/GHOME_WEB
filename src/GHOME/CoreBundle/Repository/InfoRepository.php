<?php

namespace GHOME\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * InfoRepository
 */
class InfoRepository extends EntityRepository
{
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