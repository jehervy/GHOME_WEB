<?php

namespace GHOME\CoreBundle\Controller;

use GHOME\CoreBundle\Entity\EnOceanSensor;
use GHOME\CoreBundle\Entity\Sensor;
use GHOME\CoreBundle\Entity\Rule;
use GHOME\CoreBundle\Entity\RuleCondition;
use GHOME\CoreBundle\Entity\Log;

use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Controller managing all actions related to GHOME configuration, excepted 
 * those related to the security (handled by the SecurityController).
 */
class ConfigController extends Controller
{
    /**
     * Displays the GHOME configuration homepage.
     *
     * @Route("/config")
     * @Template()
     */
     public function indexAction()
     {
         return array();
     }
     
    /**
     * Displays all rules and links to interact with them.
     *
     * @Route("/config/rules")
     * @Template()
     */
    public function rulesAction(Request $request)
    {
		$ruleManager = $this->get('ghome_core.rule_manager');
				
        return array(
            'rules' => $ruleManager->findAll(), 
        );
    }
    
    /**
     * Displays the form to add a new rule at a given position.
     *
     * @Route("/config/rules/add")
     * @Route("/config/rules/add/{position}")
     * @Template()
     */
    public function addRuleAction(Request $request, $position = null)
    {
		$ruleManager = $this->get('ghome_core.rule_manager');
		$metricManager = $this->get('ghome_core.metric_manager');
		
		if ($request->getMethod() === 'POST')
		{
		    $conditionMetric = $request->request->get('condition_metric');
		    $conditionComp = $request->request->get('condition_comp');
		    $conditionThreshold = $request->request->get('condition_threshold');
		    $actionMetric = $request->request->get('action_metric');
		    
		    $rule = new Rule();
		    
		    //Adds each condition.
		    foreach ($conditionThreshold as $i => $threshold)
		    {
		        if (!$threshold)
		        {
		            continue;
		        }
		        
		        $metric = $metricManager->find($conditionMetric[$i]);
		        $rule->addCondition($metric, $conditionComp[$i], (int) $threshold);
            }
            
            //Adds each action.
            foreach ($actionMetric as $action)
            {
                list($metric, $value) = explode('-', $action);
                
                $metric = $metricManager->find($metric);
                $rule->addAction($metric, (int) $value);
            }
		    
		    //Finally adds the rule to the rule manager. The XML file 
		    //will be written here.
            $ruleManager->add($rule, $position);
            
            return $this->redirect($this->generateUrl('ghome_core_config_rules'));
        }
		
        return array(
            'metrics' => $metricManager->findAll(),
            'comparators' => RuleCondition::getComparators(),
        );
    }
    
    /**
     * Displays a form to delete an existing rule.
     *
     * @param Request $request
     * @param integer $position
     *
     * @Route("/config/rules/delete/{position}")
     * @Template()
     */
    public function deleteRuleAction(Request $request, $position)
    {
        $ruleManager = $this->get('ghome_core.rule_manager');
        $rule = $ruleManager->find($position);
        if (!$rule)
        {
            throw new NotFoundHttpException();
        }
		
		if ($request->getMethod() === 'POST')
		{
		    if ($request->request->has('confirm'))
		    {
		        $ruleManager->remove($position);
            }
            
            return $this->redirect($this->generateUrl('ghome_core_config_rules'));
        }
        
        return array('rule' => $rule);
    }
    
    /**
     * Displays all sensors and links to interact with them.
     *
     * @Route("/config/sensors")
     * @Template()
     */
    public function sensorsAction(Request $request)
    {
		$sensorManager = $this->get('ghome_core.sensor_manager');
				
        return array(
            'sensors' => $this->mergeEnOceanSensors($sensorManager->findAll()), 
        );
    }
    
    /**
     * Displays the form to add a new rule at a given position.
     *
     * @Route("/config/sensors/add")
     * @Template()
     */
    public function addSensorAction(Request $request)
    {
		$sensorManager = $this->get('ghome_core.sensor_manager');
		$eSensorManager = $this->get('ghome_core.enocean_sensor_manager');
		$metricManager = $this->get('ghome_core.metric_manager');
		$roomManager = $this->get('ghome_core.room_manager');
		
		if ($request->getMethod() === 'POST')
		{
		    $eSensor = new EnOceanSensor();
		    $eSensor->setPhysicalId((int) $request->request->get('physical_id'));
		    $eSensor->setValid((int) $request->request->get('valid'));
		    $eSensor->setDataType((string) $request->request->get('data_type'));
		    $eSensor->setDataPos((int) $request->request->get('data_pos'));
		    $eSensor->setDataLength((int) $request->request->get('data_length'));
		    
		    $data = $request->request->get('data');
		    if ($eSensor->getDataType() === 'numeric')
		    {
		        $eSensor->setDataMin((int) $data['min']);
		        $eSensor->setDataMax((int) $data['max']);
	        }
	        elseif ($eSensor->getDataType() === 'binary')
	        {
	            foreach ($data as $i => $row)
	            {
	                if (is_numeric($i) && isset($row['binary']) && isset($row['real']) && $row['binary'] !== '' && $row['real'] !== '')
	                {
	                    $eSensor->addDataValue($row['binary'], $row['real']);
                    }
	            }
	        }
	        
	        $eSensorManager->add($eSensor);
		    
		    $metric = $metricManager->find($request->request->get('metric'));
		    $room = $roomManager->find($request->request->get('room'));
		    $sensor = new Sensor($eSensor->getVirtualId(), $metric, $room);
            $sensorManager->add($sensor);
            
            return $this->redirect($this->generateUrl('ghome_core_config_sensors'));
        }
		
        return array(
            'metrics' => $metricManager->findAll(),
            'rooms' => $roomManager->findAll(),
            'types' => EnOceanSensor::getTypes(),
        );
    }
    
    /**
     * Displays a form to delete an existing sensor.
     *
     * @param Request $request
     * @param integer $id
     *
     * @Route("/config/sensors/delete/{id}")
     * @Template()
     */
    public function deleteSensorAction(Request $request, $id)
    {
        $eSensorManager = $this->get('ghome_core.enocean_sensor_manager');
        $sensorManager = $this->get('ghome_core.sensor_manager');
        
        $eSensor = $eSensorManager->find($id);
        $sensor = $sensorManager->find($id);
        if (!$sensor || !$eSensor)
        {
            throw new NotFoundHttpException();
        }
		
		if ($request->getMethod() === 'POST')
		{
		    if ($request->request->has('confirm'))
		    {
		        $eSensorManager->remove($id);
		        $sensorManager->remove($id);
            }
            
            return $this->redirect($this->generateUrl('ghome_core_config_sensors'));
        }
        
        return array('sensor' => $sensor, 'enocean' => $eSensor);
    }
    
    /**
     * Displays a table with the logs.
     *
     * @Route("/config/logs")
     * @Template()
     */
    public function logsAction(Request $request)
    {
		$em = $this->getDoctrine()->getEntityManager();
		$adapter = new DoctrineORMAdapter($em->getRepository('GHOMECoreBundle:Log')->queryAllOrderedByDate());
		$page = $request->query->has('page') ? $request->query->get('page') : 1;
		
		$pagerfanta = new Pagerfanta($adapter);
		$pagerfanta->setMaxPerPage(15);
		$pagerfanta->setCurrentPage($page);
		
        return array('pager' => $pagerfanta);
    }
    
    private function mergeEnOceanSensors($sensors)
    {
        $eSensorManager = $this->get('ghome_core.enocean_sensor_manager');
        $data = array();
        
        foreach ($sensors as $i => $sensor)
        {
            $data[$i]['sensor'] = $sensor;
            $data[$i]['room'] = $sensor->getRoom();
            $data[$i]['metric'] = $sensor->getMetric();
            $data[$i]['enocean'] = $eSensorManager->find($sensor->getId());
        }
        
        return $data;
    }
}
