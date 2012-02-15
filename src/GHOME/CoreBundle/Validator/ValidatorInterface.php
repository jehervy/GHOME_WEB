<?php

namespace GHOME\CoreBundle\Validator;

interface ValidatorInterface
{
    function validate($value);
    
    function getValues();
}