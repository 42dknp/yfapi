<?php declare(strict_types=1);
/**
 * Copyright 2023 Dominic Kneup.
 *
 * Licensed under the MIT License, you find the LICENSE file in the projects 
 * root folder; you may not use this file except in compliance with the License.
 */

namespace yfAPI\API\Validators;

use yfAPI\API\Interfaces\QuoteValidatorInterface;
use yfAPI\Exceptions\ValidatorExceptions;

/**
 * Class QuoteValidator
 *
 * @category Validator
 * @package  42dknp/yfapi-php
 * @author   Dominic Kneup
 * @license  MIT License
 * @link     https://github.com/42dknp/yfapi-php
 */
class QuoteValidator implements QuoteValidatorInterface
{

    /**
     * Define required Properties to check
     * 
     * @var string[] $requiredProperties
     */
    public static array $requiredProperties = [
        'currency', 
        'symbol', 
        'fullExchangeName',
         'firstTradeDateMilliseconds', 
         'exchangeTimezoneName', 
         'regularMarketPrice', 
         'priceHint',
    ]; 

    /**
     * Define required multi-dimensional Properties to check
     * 
     * @var string[] $multiDimensionals
     */
    public static array $multiDimensionals = [
        'fiftyTwoWeekLowChange',
        'fiftyTwoWeekHighChangePercent',
        'regularMarketDayRange',
        'regularMarketDayHigh',
        'fiftyTwoWeekHigh',
        'regularMarketPreviousClose',
        'fiftyTwoWeekHighChange',
        'marketCap',
        'regularMarketChange',
        'fiftyTwoWeekRange',
        'regularMarketVolume',
        'regularMarketDayLow',
    ];

    /**
     * Validate Quote Results
     * 
     * @param object $data
     * 
     * @throws ValidatorExceptions
     * @return void
     */
    public static function validateResults(object $data): void 
    {
             
        $combined = array_merge(self::$requiredProperties, self::$multiDimensionals);
        
        foreach ($combined as $property) {

            // Check if the property exists for the first element in the result array
            if (!isset($data->quoteResponse->result[0]->$property)) {

                throw new ValidatorExceptions('Missing ' . $property . ' property');

            }
        
        }  
        
        foreach (self::$multiDimensionals as $property) {

            // Check if the property exists and has the required sub-properties
            if (!isset($data->quoteResponse->result[0]->$property) 
                || !isset($data->quoteResponse->result[0]->$property->raw) 
                || !isset($data->quoteResponse->result[0]->$property->fmt)
            ) {

                throw new ValidatorExceptions('Invalid sub-properties for ' . $property);

            }
        }
    }
}