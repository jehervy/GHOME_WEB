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

/**
 * Default controller, handling all actions related to the GHOME sensors 
 * and actuators.
 */
class DefaultController extends Controller
{
    /**
     * Displays the homepage with a summary of all recent data.
     *
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
	 * Displays the summary of all recent data about a specific metric.
	 *
	 * @param integer $id The identifier of the metric were're talking about
	 *
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
	 * Displays the summary of all recent data about a specific room.
 	 *
 	 * @param integer $id The identifier of the room were're talking about
 	 *
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
	 * Displays all data about a specific sensor (= a combination of a metric 
	 * and a room).
	 *
	 * @param Request $request
	 * @param integer $metridIc The identifier of the metric were're talking about
	 * @param integer $roomId he identifier of the room were're talking about
	 *
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
			$value = (int) $request->request->get('do');
			
			$username = $user = $this->get('security.context')->getToken()->getUser()->getUsername();
			$action = new Action($room, $metric, $value, $username);
			$this->get('ghome_core.socket_client')->sendAction($action);
			
			$em->persist($action);
			$em->flush();
			
			return $this->redirect($this->generateUrl(
				'ghome_core_default_sensor', 
				array('metricId' => $metricId, 'roomId' => $roomId)
			));
		}
		
		$infos = $em->getRepository('GHOMECoreBundle:Info')->findByMetricAndRoom($metricId, $roomId);
		$actions = $em->getRepository('GHOMECoreBundle:Action')->findByMetricAndRoom($metricId, $roomId);
		
		$adapter = new ArrayAdapter($this->mergeInfosAndActionsByTime($infos, $actions));
		$page = $request->query->has('page') ? $request->query->get('page') : 1;
		
		$pagerfanta = new Pagerfanta($adapter);
		$pagerfanta->setMaxPerPage(15);
		$pagerfanta->setCurrentPage($page);
		
		return array(
			'metric' => $metric,
			'room' => $room, 
			'infos' => $infos,
			'actions' => $actions,
			'pager' => $pagerfanta,
		);
	}
	
	/**
	 * Finds a metric and throws an exception if not found.
	 *
	 * @param integer $id The identifier of the metric to find
	 * @return Metric
	 */
	private function findMetric($id)
	{
		$metric = $this->get('ghome_core.metric_manager')->find($id);
		if (!$metric)
		{
			throw new NotFoundHttpException('Metric #'.$metricId.' not found.');
		}
		
		return $metric;
	}
	
	/**
	 * Finds a room and throws an exception if not found.
	 *
	 * @param integer $id The identifier of the room to find
	 * @return Room
	 */
	private function findRoom($id)
	{
		$room = $this->get('ghome_core.room_manager')->find($id);
		if (!$room)
		{
			throw new NotFoundHttpException('Room #'.$id.' not found.');
		}
		
		return $room;
	}
	
	/**
	 * Merge informations from sensors and actuators into an unified 
	 * array according to chronological order.
	 * 
	 * @param array|Collection $infos Data from sensors
	 * @param array|Collection $actions Data from actuators
	 * @return array
	 */
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
	
	/**
	 * Merge informations from sensors and actuators into an unified 
	 * array with a row per sensor (= metric + room combination). For each 
	 * rows available keys are:
	 *
	 *   - metricEntity: the metric we're talking about
	 *   - roomEntity: the room we're talking about
	 *   - info: data from sensors for this combination
	 *   - action: data from actutors for this combination
	 * 
	 * @param array|Collection $infos Data from sensors
	 * @param array|Collection $actions Data from actuators
	 * @return array
	 */
	private function mergeInfosAndActionsBySensor($infos, $actions)
	{
	    $metricManager = $this->get('ghome_core.metric_manager');
	    $roomManager = $this->get('ghome_core.room_manager');
	    $sensorManager = $this->get('ghome_core.sensor_manager');
		$data = array();
		
		//Organize data from sensors per metric and per room.
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
		
		//Organize data from actuators per metric and per room.
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
	    
	    //Adds all remaining sensors with no data.
	    foreach ($sensorManager->findAll() as $sensor)
	    {
	        if (!isset($data[$sensor->getMetric()->getId()][$sensor->getRoom()->getId()]))
	        {
	            $data[$sensor->getMetric()->getId()][$sensor->getRoom()->getId()] = array();
	        }
	    }
		
		ksort($data);
		$retval = array();
		
		//And merge!
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
