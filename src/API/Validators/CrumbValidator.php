<?php declare(strict_types=1);
/**
 * Copyright 2023 Dominic Kneup.
 *
 * Licensed under the MIT License, you find the LICENSE file in the projects 
 * root folder; you may not use this file except in compliance with the License.
 */

namespace yfAPI\API\Validators;

use yfAPI\API\Interfaces\CrumbValidatorInterface;
use yfAPI\Exceptions\ValidatorExceptions;

/**
 * Class CrumbValidator
 *
 * @category Validator
 * @package  42dknp/yfapi-php
 * @author   Dominic Kneup
 * @license  MIT License
 * @link     https://github.com/42dknp/yfapi-php
 */
class CrumbValidator implements CrumbValidatorInterface
{

    /**
     * Validate Crumb is not empty 
     * 
     * @param string $crumb
     * 
     * @return string
     */
    public static function validateCrumb(string $crumb): string
    {
        if (!empty($crumb)) {
            return $crumb;
        } else {
            throw new ValidatorExceptions("Error: Crumb is empty.");
        }
    }
}
