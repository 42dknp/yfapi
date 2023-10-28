<?php declare(strict_types=1);
/**
 * Copyright 2023 Dominic Kneup.
 *
 * Licensed under the MIT License, you find the LICENSE file in the projects 
 * root folder; you may not use this file except in compliance with the License.
 */

namespace yfAPI\API;

use yfAPI\APIClient;
use yfAPI\API\Transformers\SearchTransformer;

/**
 * Class Search
 *
 * @category API
 * @package  42dknp/yfapi-php
 * @author   Dominic Kneup
 * @license  MIT License
 * @link     https://github.com/42dknp/yfapi-php
 */
class Search extends APIClient
{
    /**
     * API Endpoint
     * 
     * @var string $endpoint
     */
    public string $endpoint = "https://query2.finance.yahoo.com/v1/finance/search";

    /**
     *  Setup locale (optional)
     * 
     * @var string $locale
     */
    public string $locale = 'en-US';
    
    /**
     *  Setup News Count to 0
     *
     * @var int $newsCount
     */
    public int $newsCount = 0;

    /**
     * Setup quotesCount 
     *
     * @var int $quotesCount
     */
    public int $quotesCount = 5;

    /**
     * Setup Output Format
     *
     * @var string $output
     */
    public string $output = "object";

    /**
     * Search Stock by Name
     * 
     * @param string $searchTerm
     * 
     * @return mixed[]: A raw json string, or object
     */
    public function searchFor(string $searchTerm): string | array | object
    {
        $params = [
            "q" => $searchTerm,
            "lang" => $this->locale,
            "newsCount" => strval($this->newsCount),
            "quotesCount" => strval($this->quotesCount)
        ];
    
        return SearchTransformer::output($this->requestAPI($this->endpoint, $params), $this->output);
    }
}
