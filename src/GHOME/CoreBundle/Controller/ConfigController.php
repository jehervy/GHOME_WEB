<?php

namespace GHOME\CoreBundle\Controller;

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
     * Displays a table with the logs.
     *
     * @Route("/config/logs")
     * @Template()
     */
    public function logsAction(Request $request)
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
