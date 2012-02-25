<?php

namespace GHOME\CoreBundle\Formatter;

/**
 * Basic implementation of formatter supporting options.
 */
abstract class Formatter implements FormatterInterface
{
	protected $options;
	
	/**
	 * Constructor.
	 *
	 * @param array $options A list of options to use
	 */
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
	
	/**
	 * Returns default options for this formatters. All options specified 
	 * here must be passed to the constructor and options not referenced here 
	 * will be rejected.
	 *
	 * @return array
	 */
	protected function getDefaultOptions()
	{
		return array();
	}
}