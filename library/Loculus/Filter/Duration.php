<?php
namespace Loculus\Filter;

use Zend\Filter;

class Duration extends Filter\AbstractFilter {

    public function filter($value) {
        $len = strlen($value);
        switch ($len) {
            case 4:
                $value = '00:0' . $value;
                break;
            case 5:
                $value = '00:' . $value;
                break;
        }

        return $value;
    }
}
