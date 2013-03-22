<?php
/**
 * Objects converter class
 *
 * @copyright Copyright (c) 2013 Tomasz Kuter <evolic_at_interia_dot_pl>
 * @license   http://evolic.eu5.org/en/new-bsd-licence.html New BSD License
 */

namespace Loculus\Log;

/**
 * Objects converter class
 * @author Tomasz Kuter <evolic_at_interia_dot_pl>
 *
 */
class Converter
{
    /**
     * @var mixed
     */
    protected $message;

    public function __construct($message)
    {
        $this->message = $message;
    }


    public function __toString()
    {
        if (is_string($this->message) || is_numeric($this->message) || is_bool($this->message)) {
            return $this->message;
        } else if (is_array($this->message)) {
            if ($this->message) {
                $out = array();
                foreach ($this->message as $key => $value) {
                    if (is_string($value) || is_numeric($value) || is_bool($value)) {
                        $out[] = "$key=$value";
                    } else {
                        $out[] = "$key=[object]";
                    }
                }
                return implode(',', $out);
            } else {
                return 'empty array';
            }
        } else if ($this->message === null) {
            return 'null';
        }

        try {
            $vars = get_object_vars($this->message);
            if ($vars) {
                $out = array();
                foreach ($vars as $key => $value) {
                    if (is_string($value) || is_numeric($value) || is_bool($value)) {
                        $out[] = "$key=$value";
                    } else if (is_object($value)) {
                        $out[] = "$key=[object]";
                    } else {
                        $out[] = "$key=[unknown]";
                    }
                }
                $value = implode(',', $out);
            } else {
                $value = 'empty array';
            }
            return "[object:$value]";
        }
        catch(Exception $e) {
            return "[object:not convertable]";
        }
    }
}