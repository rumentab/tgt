<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

declare(strict_types=1);

namespace App\Core\Services\Validator;

/**
 * Trait InputValidator
 * @package App\Core\Services\Validator
 */
trait InputValidator
{
    /**
     * @param $var
     * @return mixed
     */
    public function validate($var): mixed
    {
        switch (true) {
            case is_numeric($var):
                $var = +$var;
                break;
            case is_array($var):
            case is_object($var):
                foreach ($var as &$v) {
                    $v = $this->validate($v);
                }
                break;
        }
        return $var;
    }
}
