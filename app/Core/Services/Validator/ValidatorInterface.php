<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

namespace App\Core\Services\Validator;


interface ValidatorInterface
{
    /**
     * @param mixed $value
     * @return mixed
     */
    public function validate($value);
}
