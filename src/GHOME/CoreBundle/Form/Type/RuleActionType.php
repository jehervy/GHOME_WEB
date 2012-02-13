<?php

namespace GHOME\CoreBundle\Form\Type;

use GHOME\CoreBundle\Form\DataTransformer\EntityToIdTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class RuleActionType extends AbstractType
{
	public function buildForm(FormBuilder $builder, array $options)
	{
	    $builder->add('metric', new MetricType(), array('choices' => $options['metrics'], 'manager' => $options['metric_manager']));
        $builder->add('value', 'integer');
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'GHOME\CoreBundle\Entity\RuleAction',
            'metrics' => array(),
            'metric_manager' => null,
        );
    }

    public function getName()
    {
        return 'ghome_core_ruleaction';
    }
}