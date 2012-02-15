<?php

namespace GHOME\CoreBundle\Validator;

abstract class Validator implements ValidatorInterface
{
    protected $options;
	
	public function __construct(array $options = array())
	{
		if ($diff = array_diff(array_keys($options), array_keys($this->getDefaultOptions())))
		{
			throw new \InvalidArgumentException('Invalid options found in '.get_class($this).': '.implode(', ', $diff).'.');
		}
		if ($diff = array_diff(array_keys($this->getDefaultOptions()), array_keys($options)))
		{
			throw new \InvalidArgumentException('Missing options in '.get_class($this).': '.implode(', ', $diff).'.');
		}
		
		$this->options = array_merge($this->getDefaultOptions(), $options);
	}
	
	protected function getDefaultOptions()
	{
		return array();
	}
}