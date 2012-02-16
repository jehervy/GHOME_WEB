<?php

namespace GHOME\CoreBundle\Controller;

use GHOME\CoreBundle\Entity\Info;
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
		$infos = $em->getRepository('GHOMECoreBundle:Info')->findLastValues();
		$actions = $em->getRepository('GHOMECoreBundle:Action')->findLastValues();
		
		$data = $this->mergeInfosAndActionsBySensor($infos, $actions);
		
        return array('infos' => $infos, 'data' => $data);
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
		$infos = $em->getRepository('GHOMECoreBundle:Info')->findByMetric($id);
		$actions = $em->getRepository('GHOMECoreBundle:Action')->findByMetric($id);
		
		$data = $this->mergeInfosAndActionsBySensor($infos, $actions);
		
        return array('metric' => $metric, 'data' => $data);
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
		$infos = $em->getRepository('GHOMECoreBundle:Info')->findByRoom($id);
		$actions = $em->getRepository('GHOMECoreBundle:Action')->findByRoom($id);
		
		$data = $this->mergeInfosAndActionsBySensor($infos, $actions);
		
		return array('room' => $room, 'data' => $data);
	}
	
	/**
	 * @Route("/sensor/{metricId}/{roomId}")
	 * @Template()
	 */
	public function sensorAction(Request $request, $metricId, $roomId)
	{
		$em = $this->getDoctrine()->getEntityManager();
		
		$room = $this->findRoom($roomId);
		$metric = $this->findMetric($metricId);
		
		if ($request->request->has('do'))
		{
			$value = $metric->validateActuatorValue($request->request->get('do'));
			
			$action = new Action($room, $metric, $value);
			//$this->get('ghome_core.socket_client')->sendAction($action);
			
			$em->persist($action);
			$em->flush();
			
			/*$fd = pfsockopen("127.0.0.1",3023, $errno, $errstr,30);
			$str = '2'.strlen($metric->getId()).$metric->getId().strlen($room->getId()).$room->getId().'1'.$value;
			fwrite($fd, $str);*/
			
			return $this->redirect($this->generateUrl(
				'ghome_core_default_sensor', 
				array('metricId' => $metricId, 'roomId' => $roomId)
			));
		}
		
		$infos = $em->getRepository('GHOMECoreBundle:Info')->findByMetricAndRoom($metricId, $roomId);
		$actions = $em->getRepository('GHOMECoreBundle:Action')->findByMetricAndRoom($metricId, $roomId);
		
		$adapter = new ArrayAdapter($this->mergeInfosAndActionsByTime($infos, $actions));
		
		$page = $request->query->has('page') ? $request->query->has('page') : 1;
		
		$pagerfanta = new Pagerfanta($adapter);
		$pagerfanta->setMaxPerPage(30);
		$pagerfanta->setCurrentPage($page);
		
		//Finds the available actions.
		if ($metric->isActuator())
		{
		    $availableActions = $metric->getActuatorValues();
		    if (isset($actions[0]))
		    {
		        unset($availableActions[array_search($actions[0]->getValue(), $availableActions)]);
		    }
		}
		
		return array(
			'metric' => $metric,
			'room' => $room, 
			'infos' => $infos,
			'actions' => $actions,
			'pager' => $pagerfanta,
			'availableActions' => $metric->isActuator() ? $availableActions : null,
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
	
	private function mergeInfosAndActionsByTime($infos, $actions)
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
		
		krsort($data);
		$retval = array();
		
		foreach ($data as $time => $rows)
		{
			$retval = array_merge($retval, $rows);
		}
		
		return $retval;
	}
	
	private function mergeInfosAndActionsBySensor($infos, $actions)
	{
	    $metricManager = $this->get('ghome_core.metric_manager');
	    $roomManager = $this->get('ghome_core.room_manager');
	    $sensorManager = $this->get('ghome_core.sensor_manager');
		$data = array();
		
		foreach ($infos as $info)
		{
			if (!isset($data[$info->getMetric()]))
			{
				$data[$info->getMetric()] = array();
			}
			if (!isset($data[$info->getMetric()][$info->getRoom()]))
			{
				$data[$info->getMetric()][$info->getRoom()] = array();
			}
			$data[$info->getMetric()][$info->getRoom()][] = $info;
		}
		
		foreach ($actions as $action)
		{
			if (!isset($data[$action->getMetric()]))
			{
				$data[$action->getMetric()] = array();
			}
			if (!isset($data[$action->getMetric()][$action->getRoom()]))
			{
				$data[$action->getMetric()][$action->getRoom()] = array();
			}
			$data[$action->getMetric()][$action->getRoom()][] = $action;
		}
	    
	    foreach ($sensorManager->findAll() as $sensor)
	    {
	        if (!isset($data[$sensor->getMetric()->getId()][$sensor->getRoom()->getId()]))
	        {
	            $data[$sensor->getMetric()->getId()][$sensor->getRoom()->getId()] = array();
	        }
	    }
		
		ksort($data);
		$retval = array();
		
		foreach ($data as $metric => $rows)
		{
		    foreach ($rows as $room => $objects)
		    {
		        $temp = array(
		            'metricEntity' => $metricManager->find($metric), 
		            'roomEntity' => $roomManager->find($room),
		            'info' => null, 
		            'action' => null,
		        );
		        foreach ($objects as $obj)
		        {
		            if ($obj instanceof Info)
		            {
		                $temp['info'] = $obj;
		            }
		            elseif ($obj instanceof Action)
		            {
		                $temp['action'] = $obj;
		            }
		        }
		        $retval[] = $temp;
		    }
		}
		
		return $retval;
	}
}
