<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

namespace App\Api\Services\Validator;


use App\Core\Services\Validator\ValidatorInterface;

class EmailValidator implements ValidatorInterface
{

    /**
     * @param mixed $value
     * @return mixed
     */
    public function validate($value)
    {
        return boolval(\filter_var($value, FILTER_SANITIZE_EMAIL));
    }
}
