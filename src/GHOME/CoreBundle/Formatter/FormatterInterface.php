<?php

namespace GHOME\CoreBundle\Formatter;

/**
 * Interface for all formatters. They are used to present in a human-readable 
 * way data extracted from sensors and actuators. The formatter is choosen by 
 * the entities.
 */
interface FormatterInterface
{
    /**
     * Formats a value to a human-readable string.
     *
     * @param mixed $value
     * @return string
     */
	function format($value);
	
	/**
	 * Returns the CSS class associated with the value when displaying it 
	 * in a Bootstrap "label" context.
	 *
	 * @param mixed $value
	 * @return string
	 */
	function getCssClass($value);
	
	/**
	 * Checks if values handled by this formatter are boolean or not.
	 *
	 * @return boolean
	 */
	function isBoolean();
}