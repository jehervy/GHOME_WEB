<?php

namespace GHOME\CoreBundle\Twig;

class GHOMEExtension extends \Twig_Extension
{
    public function getTests()
    {
        return array(
            'instanceof' => new \Twig_Test_Method($this, 'isInstanceOf'),
        );
    }

	public function getFunctions()
	{
		return array(
			'max' => new \Twig_Function_Function('max'),
		);
	}

	public function isInstanceOf($value, $class)
	{
		return ($value instanceof $class);
	}
	
	public function getName()
	{
		return 'ghome_core';
	}
}