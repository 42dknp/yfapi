<?php declare(strict_types=1);
/**
 * Copyright 2023 Dominic Kneup.
 *
 * Licensed under the MIT License, you find the LICENSE file in the projects 
 * root folder; you may not use this file except in compliance with the License.
 */

namespace yfAPI\API\Validators;

use yfAPI\API\Interfaces\ValidatorInterface;
use yfAPI\Exceptions\ValidatorExceptions;

/**
 * Class Validator
 * 
 * @category Validator
 * @package  42dknp/yfapi-php
 * @author   Dominic Kneup 
 * @license  MIT License
 * @link     https://github.com/42dknp/yfapi-php
 */
class Validator implements ValidatorInterface
{
    /**
     * Define valid Intervals
     * 
     * @var mixed[] $validIntervals
     */
    public static array $validIntervals = [
        "1d", "5d", "1wk", "1mo", "3mo", "6mo", "1y", "2y", "5y", "10y", "ytd", "max"
    ];

    /**
     * Validate URL
     *      
     * @param string $url
     * 
     * @throws ValidatorExceptions
     * 
     * @return bool
     */
    public static function validURL(string $url): bool
    {
        if (empty($url) && !preg_match('~^(https?://[^/]+)(/.*)?$~', $url)) {
            throw new ValidatorExceptions("Invalid URL");
        } else {
            return true;
        }
    }

    /**
     * Check if interval parameter is valid
     *
     * @param string $interval
     * 
     * @throws ValidatorExceptions
     * 
     * @return bool
     */
    public static function checkInterval(string $interval): bool
    {
        
        if (!empty($interval) && in_array($interval, self::$validIntervals)) {
            return true;
        } else {
            throw new ValidatorExceptions("Invalid Interval");
        }
    }

    /**
     * Check if dates are valid
     *
     * @param \DateTimeInterface $startDate
     * @param \DateTimeInterface $endDate
     * 
     * @throws ValidatorExceptions
     * @return bool
     */
    public static function validateDates(\DateTimeInterface $startDate, \DateTimeInterface $endDate): bool
    {
        if ($startDate != null && $endDate != null && $startDate > $endDate) {
            throw new ValidatorExceptions('Invalid dates');
        } else {
            return true;
        }
    }

    /**
     * Check User Agent file 
     * 
     * @param string $path
     * 
     * @throws ValidatorExceptions
     * @return bool
     */
    public static function checkUserAgentFileExists(string $path): bool
    {
        if (!file_exists($path) || !is_readable($path)) {
            throw new ValidatorExceptions('Failed to read the useragents.json file');
        }
        return true;
    }

    /**
     * Check User Agent file is empty or not an array
     * 
     * @param mixed[] $userAgents
     * 
     * @throws ValidatorExceptions
     * @return bool
     */
    public static function checkUserAgentFileEmptyOrNoArray(array $userAgents): bool
    {
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($userAgents)) {
            throw new ValidatorExceptions('Failed to decode useragents.json');
        }

        if (empty($userAgents)) {
            throw new ValidatorExceptions('No user agents found in useragents.json');
        } 
        return false;
    }
}
