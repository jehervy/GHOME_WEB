<?php

namespace GHOME\CoreBundle\Validator;

class NullOrIntegerValidator extends Validator
{
	public function validate($value)
	{
	    $value = (int) $value;
	    
	    if (!$value)
	    {
	        return false;
	    }
	    
		if (
		    (!isset($this->options['min']) || $value >= $this->options['min'])
		    && (!isset($this->options['max']) || $value <= $this->options['max'])
		)
		{
		    return $value;
		}
		
		throw new ValidationException();
	}
	
	public function getValues()
	{
	    return array_merge(array(false), range($this->options['min'], $this->options['max']));
	}
	
	protected function getDefaultOptions()
	{
		return array('min' => null, 'max' => null);
	}
}