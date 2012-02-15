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

class ConfigController extends Controller
{
    /**
     * @Route("/config")
     * @Template()
     */
     public function indexAction()
     {
         return array();
     }
     
    /**
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
     * @Route("/config/rules/add")
     * @Template()
     */
    public function addRuleAction(Request $request)
    {
		$ruleManager = $this->get('ghome_core.rule_manager');
		$metricManager = $this->get('ghome_core.metric_manager');
		
		//$rule = new Rule();
		//$form = $this->createForm(new RuleType(), $rule, array('metrics' => $metricManager->findAll(), 'metric_manager' => $metricManager));
		
		/*if ($request->getMethod() === 'POST')
		{
            $form->bindRequest($request);

            if ($form->isValid())
            {
                $ruleManager->add($rule);

                //return $this->redirect($this->generateUrl('ghome_core_config_rules'));
            }
        }*/
		
        return array(
            'metrics' => $metricManager->findAll(),
            'comparators' => RuleCondition::getComparators(),
        );
    }
    
    /**
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
