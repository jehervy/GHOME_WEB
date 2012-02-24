<?php

namespace GHOME\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

/**
 * Type for the forms framework to handle users.
 */
class EnOceanNumericSensorType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
	public function buildForm(FormBuilder $builder, array $options)
	{
	    $builder->add('physicalId', null, array('label' => 'Nom d\'utilisateur'));
	    $builder->add('valid', 'integer', array('label' => 'Masque de validation'));
	    $builder->add('dataPos', 'integer', array('label' => 'Position des données'));
	    $builder->add('dataLength', 'integer', array('label' => 'Longueur des données'));
	    $builder->add('dataMin', 'integer', array('label' => 'Valeur minimale'));
	    $builder->add('dataMax', 'integer', array('label' => 'Valeur maximale'));
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'GHOME\CoreBundle\Entity\EnOceanSensor',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ghome_core_enoceansensor';
    }
}