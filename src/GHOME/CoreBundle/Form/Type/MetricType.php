<?php

namespace GHOME\CoreBundle\Form\Type;

use GHOME\CoreBundle\Form\DataTransformer\EntityToIdTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilder;

class MetricType extends ChoiceType
{
	public function buildForm(FormBuilder $builder, array $options)
	{
	    parent::buildForm($builder, $options);
	    $builder->appendClientTransformer(new EntityToIdTransformer($options['manager']));
    }
    
    public function getDefaultOptions(array $options)
    {
        $options = parent::getDefaultOptions($options);
        $options['manager'] = null;
        
        return $options;
    }
    
    public function getName()
    {
        return 'ghome_core_metric';
    }
}