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
 * Interface ValidatorInterface
 * 
 * @category Interface
 * @package  42dknp/yfapi-php
 * @author   Dominic Kneup
 * @license  MIT License
 * @link     https://github.com/42dknp/yfapi-php
 */
interface ValidatorInterface
{

    /**
     * Validate URL
     *      
     * @param string $url
     * 
     * @throws ValidatorExceptions
     * 
     * @return bool
     */
    public static function validURL(string $url): bool;

    /**
     * Check if interval parameter is valid
     *
     * @param string $interval
     * 
     * @throws ValidatorExceptions
     * 
     * @return bool
     */
    public static function checkInterval(string $interval): bool;

    /**
     * Check if dates are valid
     *
     * @param \DateTimeInterface $startDate
     * @param \DateTimeInterface $endDate
     * 
     * @throws ValidatorExceptions
     * @return bool
     */
    public static function validateDates(\DateTimeInterface $startDate, \DateTimeInterface $endDate): bool;

    /**
     * Check User Agent file 
     * 
     * @param string $path
     * 
     * @throws ValidatorExceptions
     * @return bool
     */
    public static function checkUserAgentFileExists(string $path): bool;

    /**
     * Check User Agent file is empty or not an array
     * 
     * @param mixed[] $userAgents
     * 
     * @throws ValidatorExceptions
     * @return bool
     */
    public static function checkUserAgentFileEmptyOrNoArray(array $userAgents): bool;
}