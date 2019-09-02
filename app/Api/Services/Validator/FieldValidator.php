<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

namespace App\Api\Services\Validator;


use App\Core\Services\Validator\ValidatorInterface;

class FieldValidator implements ValidatorInterface
{
    private const ALLOWED_FIELDS = [
        'id', 'name', 'email', 'password'
    ];

    /**
     * @param string $value
     * @return bool
     */
    public function validate($value): bool
    {
        return \in_array($value, static::ALLOWED_FIELDS);
    }
}
