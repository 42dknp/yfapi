<?php declare(strict_types=1);
/**
 * Copyright 2023 Dominic Kneup.
 *
 * Licensed under the MIT License, you find the LICENSE file in the projects 
 * root folder; you may not use this file except in compliance with the License.
 */

namespace yfAPI\API\Validators;

use yfAPI\API\Interfaces\SimilarSecuritiesValidatorInterface;
use yfAPI\Exceptions\ValidatorExceptions;

/**
 * Class SimilarSecuritiesValidator
 *
 * @category Validator
 * @package  42dknp/yfapi-php
 * @author   Dominic Kneup
 * @license  MIT License
 * @link     https://github.com/42dknp/yfapi-php
 */
class SimilarSecuritiesValidator implements SimilarSecuritiesValidatorInterface
{
   
    /**
     * Define required Properties to check
     * 
     * @var string[]  $requiredProperties
     */
    public static array $requiredProperties = [
        'symbol',
        'recommendedSymbols'
    ];

    /**
     * Define required Inddicator Properties to check
     * 
     * @var string[][] $indicatorProperties
     */
    public static array $indicatorProperties = [
        'recommendedSymbols' => [
            'symbol',
            'score'
        ]
    ];

    /**
     * Transform Results to object
     *
     * @param object $data
     * 
     * @return void
     */
    public static function validateResults(object $data): void
    { 
        // Check required properties
        foreach (self::$requiredProperties as $property) {
            
            if (!isset($data->finance->result[0]->$property)) {
                
                throw new ValidatorExceptions('Missing ' . $property . ' property');
            }
            
        }

        // Check sub-properties inside "recommendedSymbols"
        if (isset($data->finance->result[0]->recommendedSymbols) && is_array($data->finance->result[0]->recommendedSymbols)) {
           
            foreach ($data->finance->result[0]->recommendedSymbols as $recommendedSymbol) {
                
                foreach (self::$indicatorProperties['recommendedSymbols'] as $subProperty) {
                   
                    if (!isset($recommendedSymbol->$subProperty)) {
                       
                        throw new ValidatorExceptions('Invalid sub-properties for ' . $subProperty);
                    }
                }

            }

        } else {

            throw new ValidatorExceptions('Missing recommendedSymbols property or it is not an array');
        
        }
    }
}
