<?php

namespace GHOME\CoreBundle\Controller;

use GHOME\CoreBundle\Entity\Log;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class LogController extends Controller
{
    /**
     * @Route("/log/")
     * @Template()
     */
    public function indexAction(Request $request)
    {
		$em = $this->getDoctrine()->getEntityManager();
		
		$adapter = new DoctrineORMAdapter($em->getRepository('GHOMECoreBundle:Log')->queryAllOrderedByDate());
		
		$page = $request->query->has('page') ? $request->query->has('page') : 1;
		
		$pagerfanta = new Pagerfanta($adapter);
		$pagerfanta->setMaxPerPage(30);
		$pagerfanta->setCurrentPage($page);
		
        return array('pager' => $pagerfanta);
    }
}
