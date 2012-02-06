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
				'GROUP BY i.metric, i.room '.
				'ORDER BY i.metric, i.date DESC')
	        ->getResult();
	}
	
	public function findByMetricAndRoom($metric, $room)
	{
	    return $this->getEntityManager()
	        ->createQuery(
				'SELECT i FROM GHOMECoreBundle:Info i '.
				'WHERE i.metric = :metric AND i.room = :room '.
				'ORDER BY i.date DESC')
			->setParameter('metric', $metric)
			->setParameter('room', $room)
	        ->getResult();
	}
}