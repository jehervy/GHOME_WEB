<?php

namespace GHOME\CoreBundle\Form\Type;

use GHOME\CoreBundle\Entity\RuleCondition;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class RuleConditionType extends AbstractType
{
	public function buildForm(FormBuilder $builder, array $options)
	{
	    $builder->add('metric', 'choice', array('choices' => $options['metrics']));
        $builder->add('comparator', 'choice', array('choices' => RuleCondition::getComparators()));
        $builder->add('threshold', 'integer');
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'GHOME\CoreBundle\Entity\RuleCondition',
            'metrics' => array(),
        );
    }

    public function getName()
    {
        return 'ghome_core_rulecondition';
    }
}