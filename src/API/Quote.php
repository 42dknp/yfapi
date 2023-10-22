<?php declare(strict_types=1);
/**
 * Copyright 2023 Dominic Kneup.
 *
 * Licensed under the MIT License, you find the LICENSE file in the projects 
 * root folder; you may not use this file except in compliance with the License.
 */

namespace yfAPI\API;

use yfAPI\API\Crumb;
use yfAPI\API\Transformers\QuoteTransformer;

/**
 * Class Quote
 *
 * @category API
 * @package  42dknp/yfapi-php
 * @author   Dominic Kneup
 * @license  MIT License
 * @link     https://github.com/42dknp/yfapi-php
 */
class Quote extends Crumb
{

    /**
     * API Endpoint URL
     * 
     * @var string $endpoint
     */
    public string $endpoint = "https://query2.finance.yahoo.com/v7/finance/quote";
    
    /**
     * Allowed fields for API Call
     * 
     * @var array<string> $allowedFields
     */
    public array $allowedFields = [
        "longName",
        "shortName",
        "regularMarketPrice",
        "regularMarketChange",
        "regularMarketChangePercent",
        "messageBoardId",
        "marketCap",
        "underlyingSymbol",
        "underlyingExchangeSymbol",
        "headSymbolAsString",
        "regularMarketVolume",
        "uuid",
        "regularMarketOpen",
        "fiftyTwoWeekLow",
        "fiftyTwoWeekHigh",
        "toCurrency",
        "fromCurrency",
        "toExchange",
        "fromExchange",
        "corporateActions"
    ];

    /**
     * Setup CorsDomain
     * 
     * @var string $corsDomain
     */
    public string $corsDomain = "finance.yahoo.com";

    /**
     * Setup Region
     * 
     * @var string $region
     */
    public string $region = "US";

    /**
     * Setup Language
     * 
     * @var string $language
     */
    public string $language = "en-US";

    /**
     * Setup Ouput Format
     * 
     * @var string $formatted
     */
    public string $formatted = "true";

    /**
     * Setup Default Output Format
     * 
     * @var string $output
     */
    public string $output = "object";

    /**
     * Get current Quote (prices etc.)
     * 
     * @param string $symboll: The Security / Stock symbol
     * @param array<string> $fields
     *      
     * @return mixed[]: A raw json string, object or array
     */
    public function getQuote(string $symbol, array $fields = []): string | object | array
    {
        // get a fresh session cookie (instance-based) and crumb (crumb response as $crumb)
        $crumb = $this->getCrumb();
        
        // URL Parameter as array
        $params = [
            'formatted' => $this->formatted,
            'crumb' => $crumb,
            'lang' => $this->language,
            'region' => $this->region,
            'symbols' => $symbol, 
            'fields' => QuoteTransformer::transformFields($this->allowedFields, $this->allowedFields),
            'corsDomain' => $this->corsDomain
        ];
        
        // use the same session cookie as $crumb to recieve quote data and return Raw json
        $response = $this->requestAPI($this->endpoint, $params);

        return QuoteTransformer::output($response, $this->output);
    }   
}