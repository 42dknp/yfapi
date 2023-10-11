<?php declare(strict_types=1);
/**
 * Copyright 2023 Dominic Kneup.
 *
 * Licensed under the MIT License, you find the LICENSE file in the projects 
 * root folder; you may not use this file except in compliance with the License.
 */

namespace yfAPI\API\Interfaces;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;
use yfAPI\Exceptions\APIClientException;

/**
 * Interface ApiClientInterface
 * 
 * @category Interface
 * @package  42dknp/yfapi-php
 * @author   Dominic Kneup
 * @license  MIT License
 * @link     https://github.com/42dknp/yfapi-php
 */
interface APIClientInterface
{

    /**
     * Send GET request with $url to endpoint and return answer
     * 
     * @param string            $url 
     * @param array<string|int> $params 
     * 
     * @throws RequestException
     * @throws ConnectException
     * 
     * @return string
     */
    public function requestAPI(string $url, array $params = []): string;

    /**
     * Get a random User Agent from useragent.json file
     * 
     * @throws APIClientException
     * 
     * @return mixed
     */
    public static function getRandomUserAgent(): mixed;
    
}