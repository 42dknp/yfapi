<?php declare(strict_types=1);
/**
 * Copyright 2023 Dominic Kneup.
 *
 * Licensed under the MIT License, you find the LICENSE file in the projects 
 * root folder; you may not use this file except in compliance with the License.
 */

namespace yfAPI\API\Transformers;

use yfAPI\API\Interfaces\SimilarSecuritiesTransformerInterface;
use yfAPI\API\Validators\SimilarSecuritiesValidator;
use yfAPI\Exceptions\TransformerExceptions;

/**
 * Class SimilarSecuritiesTransformer
 *
 * @category Transformer
 * @package  42dknp/yfapi-php
 * @author   Dominic Kneup
 * @license  MIT License
 * @link     https://github.com/42dknp/yfapi-php
 */ 
class SimilarSecuritiesTransformer implements SimilarSecuritiesTransformerInterface
{

    /**
     * Stores results
     * 
     * @var mixed[] $result
     */
    public static $result = [];

    /**
     * Transform Results
     * 
     * @param string $data: The raw json from API as input
     * 
     * @throws TransformerExceptions
     * 
     * @return mixed[]: Array with Similar Securities Data
     */
    public static function transform(string $data): array
    {
        try {
            $result = (object) Transformer::decodeJsonResponse($data);

            // Validate object
            SimilarSecuritiesValidator::validateResults((object) $result);

            $symbols = [];

            foreach ($result->finance->result as $item) {

                if (isset($item->recommendedSymbols) && is_array($item->recommendedSymbols)) {
                
                    foreach ($item->recommendedSymbols as $recommendedSymbol) {
                        
                        if (isset($recommendedSymbol->symbol)) {
                            
                            $symbols[] = $recommendedSymbol->symbol;
                        }
                    }
                }
            }

            // Return symbols as string
            return $symbols;
            
        } catch (\Exception $e) {

            throw new TransformerExceptions("Error transform Similar Securities Data.");
        
        }
    }
     
    /**
     * Takes raw json from API response and converts / formats it
     *
     * @param string $data: raw json as input
     * @param string $format: either raw (string) or array
     * 
     * @throws TransformerExceptions
     * 
     * @return mixed[]: Return converted and formatted data
     */
    public static function output(string $data, string $format): array | string
    {   
        switch ($format){
        case "raw":
            return $data;
        case "array":
            return self::transform($data);
        default:
            throw new TransformerExceptions("Output Format invalid");
        }
    }
}
