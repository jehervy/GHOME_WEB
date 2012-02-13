<?php

namespace GHOME\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class RuleType extends AbstractType
{
	public function buildForm(FormBuilder $builder, array $options)
	{
	    $metrics = array();
	    foreach ($options['metrics'] as $metric)
	    {
	        $metrics[$metric->getId()] = $metric->getName();
	    }
	    
        $builder->add('conditions', 'collection', array(
            'type' => new RuleConditionType(), 
            'options' => array('metrics' => $metrics),
            'allow_add' => true,
            'allow_delete' => true,
        ));
		$builder->add('actions', 'collection', array(
		    'type' => new RuleActionType(),
		    'options' => array('metrics' => $metrics, 'metric_manager' => $options['metric_manager']),
		    'allow_add' => true,
            'allow_delete' => true,
		));
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'GHOME\CoreBundle\Entity\Rule',
            'metrics' => array(),
            'metric_manager' => null,
        );
    }

    public function getName()
    {
        return 'ghome_core_rule';
    }
}