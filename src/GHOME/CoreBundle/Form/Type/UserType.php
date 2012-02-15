<?php

namespace GHOME\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class UserType extends AbstractType
{
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

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'GHOME\CoreBundle\Entity\User',
        );
    }

    public function getName()
    {
        return 'ghome_core_user';
    }
}