<?php declare(strict_types=1);
/**
 * Copyright 2023 Dominic Kneup.
 *
 * Licensed under the MIT License, you find the LICENSE file in the projects 
 * root folder; you may not use this file except in compliance with the License.
 */

namespace yfAPI\API;

use yfAPI\APIClient;
use yfAPI\API\Validators\CrumbValidator;

/**
 * Class Crumb
 *
 * @category API
 * @package  42dknp/yfapi-php
 * @author   Dominic Kneup
 * @license  MIT License
 * @link     https://github.com/42dknp/yfapi-php
 */
class Crumb extends APIClient
{

    /**
     * API Endpoint URL
     * 
     * @var string $crumbEndpoint
     */
    public string $crumbEndpoint = 'https://query1.finance.yahoo.com/v1/test/getcrumb';
    
    /**
     * Define Endpoint to recieve neccecary Cookie (for further use)
     * 
     * @var string $cookieEnpoint
     */
    public string $cookieEndpoint = 'https://fc.yahoo.com';

    /**
     * Get Yahoo Finance Crumb 
     * 
     * @return string
     */
    public function getCrumb(): string
    {
        // Request cookies from $cookieEndpoint Endpoint
        $this->requestAPI($this->cookieEndpoint);

        // Request crumb from $crumbEndpoint Endpoint
        $crumb = $this->requestAPI($this->crumbEndpoint);

        // Validate and return the crumb
        return CrumbValidator::validateCrumb($crumb);
    }
}