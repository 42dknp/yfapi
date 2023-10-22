<?php declare(strict_types=1);
/**
 * Copyright 2023 Dominic Kneup.
 *
 * Licensed under the MIT License, you find the LICENSE file in the projects 
 * root folder; you may not use this file except in compliance with the License.
 */

namespace yfAPI\API;

use yfAPI\APIClient;
use yfAPI\API\Transformers\SimilarSecuritiesTransformer;

/**
 * Class SimilarSecurities
 *
 * @category API
 * @package  42dknp/yfapi-php
 * @author   Dominic Kneup
 * @license  MIT License
 * @link     https://github.com/42dknp/yfapi-php
 */
class SimilarSecurities extends APIClient
{

    /**
     * Yahoo Finance API Endpoint to request similar Securities
     * 
     * @var string $apiEndpoint
     */
    public string $apiEndpoint = "https://query2.finance.yahoo.com/v6/finance/recommendationsbysymbol/";
    
    /**
     * Setup Default Output Format
     * 
     * @var string $output
     */
    public string $output = "array";

    /**
     * Get similar Securities API Call
     *
     * @param string $security: The Security / Stock symbol
     * 
     * @return mixed[]: A raw json string, or array
     */
    public function getSimilarSecurities(string $security): array | string
    {
        $url = $this->apiEndpoint . $security;

        $response = $this->requestAPI($url);

        return SimilarSecuritiesTransformer::output($response, $this->output); 
    } 
}
