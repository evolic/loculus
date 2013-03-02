<?php

namespace Loculus\InputFilter;

use Zend\InputFilter\InputFilter as ZendInputFilter;

class InputFilter extends ZendInputFilter
{
    public function __toString()
    {
        return 'Zend\InputFilter\InputFilter';
    }
}