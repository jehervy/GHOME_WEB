<?php

namespace GHOME\CoreBundle\Formatter;

interface FormatterInterface
{
	function format($value);
	
	function getCssClass($value);
}