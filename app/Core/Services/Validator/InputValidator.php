<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

namespace App\Core\Services\Validator;


trait InputValidator
{
    /**
     * @param $var
     * @return mixed
     */
    public function validate($var)
    {
        switch (true) {
            case is_numeric($var):
                $var = +$var;
                break;
            case is_array($var):
            case is_object($var):
                foreach ($var as $k => &$v) {
                    $v = $this->validate($v);
                }
                break;
        }
        return $var;
    }
}
