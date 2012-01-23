<?php

namespace GHOME\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * InfoRepository
 */
class InfoRepository extends EntityRepository
{
	public function findByMetric($metric)
	{
	    return $this->getEntityManager()
	        ->createQuery(
				'SELECT i FROM GHOMECoreBundle:Info i '.
				'WHERE i.metric = :metric '.
				'GROUP BY i.room '.
				'ORDER BY i.date DESC')
			->setParameter('metric', $metric)
	        ->getResult();
	}
}