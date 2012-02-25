<?php

namespace GHOME\CoreBundle\Twig;

/**
 * Adds some capabilities to the Twig views.
 */
class GHOMEExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getTests()
    {
        return array(
            'instanceof' => new \Twig_Test_Method($this, 'isInstanceOf'),
        );
    }

    /**
     * {@inheritdoc}
     */
	public function getFunctions()
	{
		return array(
			'max' => new \Twig_Function_Function('max'),
		);
	}

    /**
     * Checks if some object is an instance of a class.
     *
     * @param object $value The object to test
     * @param object|string $class The class to match
     * @return boolean
     */
	public function isInstanceOf($value, $class)
	{
		return ($value instanceof $class);
	}
	
	/**
     * {@inheritdoc}
     */
	public function getName()
	{
		return 'ghome_core';
	}
}