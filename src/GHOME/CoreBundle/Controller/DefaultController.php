<?php

namespace GHOME\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
		$config = $this->get('ghome_core.configuration_manager');
		$em = $this->getDoctrine()->getEntityManager();
		
		$metrics = $config->getMetrics();
		
		foreach ($metrics as $i => $metric)
		{
			foreach ($em->getRepository('GHOMECoreBundle:Info')->findByMetric($i) as $j => $info)
			{
				$metrics[$i]['infos'][$j] = array('details' => $info, 'room' => $config->getRoom($info->getRoom()));
			}
		}
		
        return array('metrics' => $metrics);
    }
}
