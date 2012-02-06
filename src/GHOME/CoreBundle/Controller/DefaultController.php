<?php

namespace GHOME\CoreBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
		$roomManager = $this->get('ghome_core.room_manager');
		$metricManager = $this->get('ghome_core.metric_manager');
		$em = $this->getDoctrine()->getEntityManager();
		
		$infos = $this->finishHydration($em->getRepository('GHOMECoreBundle:Info')->findLastValues());
		
        return array('infos' => $infos);
    }

	/**
	 * @Route("/metric/{id}")
	 * @Template()
	 */
	public function metricAction($id)
	{
		$metricManager = $this->get('ghome_core.metric_manager');
		$em = $this->getDoctrine()->getEntityManager();
		
		$metric = $this->findMetric($id);
		$infos = $this->finishHydration($em->getRepository('GHOMECoreBundle:Info')->findByMetric($id));
		
        return array('metric' => $metric, 'infos' => $infos);
	}
	
	/**
	 * @Route("/room/{id}")
	 * @Template()
	 */
	public function roomAction($id)
	{
		$roomManager = $this->get('ghome_core.room_manager');
		$em = $this->getDoctrine()->getEntityManager();
		
		$room = $this->findRoom($id);
		$infos = $this->finishHydration($em->getRepository('GHOMECoreBundle:Info')->findByRoom($id));
		
		return array('room' => $room, 'infos' => $infos);
	}
	
	/**
	 * @Route("/sensor/{metricId}/{roomId}")
	 * @Template()
	 */
	public function roomMetricAction($metricId, $roomId)
	{
		$em = $this->getDoctrine()->getEntityManager();
		
		$room = $this->findRoom($roomId);
		$metric = $this->findMetric($metricId);
		$infos = $this->finishHydration($em->getRepository('GHOMECoreBundle:Info')->findByMetricAndRoom($metricId, $roomId));
				
		return array(
			'metric' => $metric,
			'room' => $room, 
			'infos' => $infos,
		);
	}
	
	private function findMetric($id)
	{
		$metric = $this->get('ghome_core.metric_manager')->find($id);
		
		if (!$metric)
		{
			throw new NotFoundHttpException('Metric #'.$metricId.' not found.');
		}
		
		return $metric;
	}
	
	private function findRoom($id)
	{
		$room = $this->get('ghome_core.room_manager')->find($id);
		
		if (!$room)
		{
			throw new NotFoundHttpException('Room #'.$id.' not found.');
		}
		
		return $room;
	}
	
	private function finishHydration($infos)
	{
		$roomManager = $this->get('ghome_core.room_manager');
		$metricManager = $this->get('ghome_core.metric_manager');
		
		foreach ($infos as $i => $info)
		{
			$infos[$i]->setRoomEntity($roomManager->find($info->getRoom()));
			$infos[$i]->setMetricEntity($metricManager->find($info->getMetric()));
		}
		
		return $infos;
	}
}
