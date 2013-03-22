<?php
/**
 * Duration filter class
 *
 * @copyright Copyright (c) 2013 Tomasz Kuter <evolic_at_interia_dot_pl>
 * @license   http://evolic.eu5.org/en/new-bsd-licence.html New BSD License
 */

namespace Loculus\Filter;

use Zend\Filter;

/**
 * Duration filter class
 * @author Tomasz Kuter <evolic_at_interia_dot_pl>
 *
 */
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
