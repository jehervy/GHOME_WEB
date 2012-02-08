<?php

namespace GHOME\CoreBundle\Controller;

use GHOME\CoreBundle\Entity\Action;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
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
	public function sensorAction($metricId, $roomId, Request $request)
	{
		$em = $this->getDoctrine()->getEntityManager();
		
		$room = $this->findRoom($roomId);
		$metric = $this->findMetric($metricId);
		
		if ($request->query->has('do'))
		{
			//TODO: factorize in a service.
			$value = (bool) $request->query->get('do');
			$fd = pfsockopen("127.0.0.1",3023, $errno, $errstr,30);
			$str = '2'.strlen($metric->getId()).$metric->getId().strlen($room->getId()).$room->getId().'1'.$value;
			fwrite($fd, $str);
			
			$action = new Action($room, $metric, $value);
			$em->persist($action);
			$em->flush();
			
			return $this->redirect($this->generateUrl(
				'ghome_core_default_sensor', 
				array('metricId' => $metricId, 'roomId' => $roomId)
			));
		}
		
		$infos = $this->finishHydration($em->getRepository('GHOMECoreBundle:Info')->findByMetricAndRoom($metricId, $roomId));
		$actions = $em->getRepository('GHOMECoreBundle:Action')->findByMetricAndRoom($metricId, $roomId);
		
		$adapter = new ArrayAdapter($this->mergeInfosAndActions($infos, $actions));
		
		$page = $request->query->has('page') ? $request->query->has('page') : 1;
		
		$pagerfanta = new Pagerfanta($adapter);
		$pagerfanta->setMaxPerPage(30);
		$pagerfanta->setCurrentPage($page);
		
		return array(
			'metric' => $metric,
			'room' => $room, 
			'infos' => $infos,
			'actions' => $actions,
			'pager' => $pagerfanta,
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
	
	private function mergeInfosAndActions($infos, $actions)
	{
		$data = array();
		foreach ($infos as $info)
		{
			if (!isset($data[$info->getTimestamp()]))
			{
				$data[$info->getTimestamp()] = array();
			}
			$data[$info->getTimestamp()][] = $info;
		}
		
		foreach ($actions as $action)
		{
			if (!isset($data[$action->getTimestamp()]))
			{
				$data[$action->getTimestamp()] = array();
			}
			$data[$action->getTimestamp()][] = $action;
		}
		
		ksort($data);
		$retval = array();
		
		foreach ($data as $time => $rows)
		{
			$retval = array_merge($retval, $rows);
		}
		
		return $retval;
	}
}
