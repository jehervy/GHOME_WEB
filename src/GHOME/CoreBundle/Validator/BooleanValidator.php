<?php

namespace GHOME\CoreBundle\Validator;

class BooleanValidator extends Validator
{
	public function validate($value)
	{
	    return (boolean) $value;
	}
	
	public function getValues()
	{
	    return array(true, false);
	}
}