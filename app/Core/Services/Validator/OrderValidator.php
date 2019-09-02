<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

namespace App\Core\Services\Validator;


class OrderValidator implements ValidatorInterface
{
    /**
     * @param string $value
     * @return bool
     */
    public function validate($value)
    {
        return \in_array(\strtoupper($value), ['ASC', 'DESC']);
    }
}
