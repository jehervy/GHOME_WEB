<?php

namespace GHOME\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * LogRepository
 */
class LogRepository extends EntityRepository
{
    /**
     * Returns the query to retrieve all logs ordered by descending date.
     *
     * @return Query
     */
	public function queryAllOrderedByDate()
	{
		return $this->getEntityManager()
	        ->createQuery(
				'SELECT l FROM GHOMECoreBundle:Log l '.
				'ORDER BY l.time DESC');
	}
}