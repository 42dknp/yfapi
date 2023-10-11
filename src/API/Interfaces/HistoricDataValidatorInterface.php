<?php declare(strict_types=1);
/**
 * Copyright 2023 Dominic Kneup.
 *
 * Licensed under the MIT License, you find the LICENSE file in the projects 
 * root folder; you may not use this file except in compliance with the License.
 */

namespace yfAPI\API\Interfaces;

use yfAPI\Exceptions\ValidatorExceptions;

/**
 * Interface HistoricDataValidatorInterface
 * 
 * @category Interface
 * @package  42dknp/yfapi-php
 * @author   Dominic Kneup
 * @license  MIT License
 * @link     https://github.com/42dknp/yfapi-php
 */
interface HistoricDataValidatorInterface
{

    /**
     * Validate Historic Data Results  
     *
     * @param object $data
     * 
     * @throws ValidatorExceptions
     * @return void
     */
    public static function validateResults(object $data): void;

}