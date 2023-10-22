<?php declare(strict_types=1);
/**
 * Copyright 2023 Dominic Kneup.
 *
 * Licensed under the MIT License, you find the LICENSE file in the projects 
 * root folder; you may not use this file except in compliance with the License.
 */

namespace yfAPI\API\Validators;

use yfAPI\API\Interfaces\HistoricDataValidatorInterface;
use yfAPI\Exceptions\ValidatorExceptions;

/**
 * Class HistoricDataValidator
 *
 * @category Validator
 * @package  42dknp/yfapi-php
 * @author   Dominic Kneup
 * @license  MIT License
 * @link     https://github.com/42dknp/yfapi-php
 */
class HistoricDataValidator implements HistoricDataValidatorInterface
{
    /**
     * Define required Properties to check
     * 
     * @var string[] $requiredProperties
     */
    public static array $requiredProperties = [
            'currency', 
            'symbol', 
            'exchangeName', 
            'instrumentType', 
            'firstTradeDate', 
            'timezone', 
            'exchangeTimezoneName', 
            'regularMarketPrice', 
            'chartPreviousClose', 
            'priceHint'
    ];

    /**
     * Define required Inddicator Properties to check
     * 
     * @var string[][] $indicatorProperties
     */
    public static array $indicatorProperties = [
        'quote' => [
            'low', 
            'high', 
            'volume', 
            'close', 
            'open'
        ],
        'adjclose' => ['adjclose']
    ];

    /**
     * Validate Historic Data Results  
     *
     * @param object $data: Data to validate
     * 
     * @throws ValidatorExceptions
     * @return void: None
     */
    public static function validateResults(object $data): void
    {
        
        // check "non-array" properties
        foreach (self::$requiredProperties as $property ) {
            
            if (!isset($data->chart->result[0]->meta->$property)) {
                
                throw new ValidatorExceptions('Missing ' . $property . ' property');
            }

        }

        // check "quote" and "adjclose" properties
        foreach (self::$indicatorProperties as $property => $subProperties) {
            
            if (!isset($data->chart->result[0]->indicators->$property)) {
               
                throw new ValidatorExceptions('Missing ' . $property . ' property');
            } 

        }

        // Check sub-properties inside "quote"
        if(is_array(self::$indicatorProperties["quote"])) :
           
            foreach (self::$indicatorProperties["quote"] as $quoteProperties ) {
                
                if (!isset($data->chart->result[0]->indicators->quote[0]->$quoteProperties)) {
                    
                    throw new ValidatorExceptions('Invalid sub-properties for ' . $quoteProperties);
                }

            }

        endif;

        // Check sub-properties inside "adjclose"
        if (is_array(self::$indicatorProperties["adjclose"])) {
            
            foreach (self::$indicatorProperties["adjclose"] as $quoteProperties) {
                
                if (!isset($data->chart->result[0]->indicators->adjclose[0]->$quoteProperties)) {
                   
                    throw new ValidatorExceptions('Invalid sub-properties for ' . $quoteProperties);
                }

            }

        }
    }
}
