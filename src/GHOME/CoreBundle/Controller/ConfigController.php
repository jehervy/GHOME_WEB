<?php

namespace GHOME\CoreBundle\Controller;

use GHOME\CoreBundle\Entity\Rule;
use GHOME\CoreBundle\Entity\RuleCondition;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ConfigController extends Controller
{
    /**
     * @Route("/config/rules")
     * @Template()
     */
    public function rulesAction(Request $request)
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
            'rules' => $ruleManager->findAll(), 
            'metrics' => $metricManager->findAll(),
            'comparators' => RuleCondition::getComparators(),
        );
    }
}
