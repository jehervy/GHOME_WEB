<?php

namespace GHOME\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

/**
 * Type for the forms framework to handle users.
 */
class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
	public function buildForm(FormBuilder $builder, array $options)
	{
	    $builder->add('username', null, array('label' => 'Nom d\'utilisateur'));
	    $builder->add('password', 'repeated', array(
	        'type' => 'password', 
	        'first_name' => 'Mot de passe', 
	        'second_name' => 'Répétez le mot de passe',
	    ));
	    $builder->add('admin', null, array(
	        'label' => 'Administrateur ?', 
	        'required' => false,
	    ));
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'GHOME\CoreBundle\Entity\User',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ghome_core_user';
    }
}