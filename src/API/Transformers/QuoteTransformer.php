<?php declare(strict_types=1);
/**
 * Copyright 2023 Dominic Kneup.
 *
 * Licensed under the MIT License, you find the LICENSE file in the projects 
 * root folder; you may not use this file except in compliance with the License.
 */

namespace yfAPI\API\Transformers;

use yfAPI\API\Interfaces\QuoteTransformerInterface;
use yfAPI\API\Transformers\Transformer;
use yfAPI\API\Validators\QuoteValidator;
use yfAPI\Exceptions\TransformerExceptions;

/**
 * Class QuoteTransformer
 *
 * @category Transformer
 * @package  42dknp/yfapi-php
 * @author   Dominic Kneup
 * @license  MIT License
 * @link     https://github.com/42dknp/yfapi-php
 */ 
class QuoteTransformer implements QuoteTransformerInterface
{

    /**
     * Transform API result
     * 
     * @param string $result: The raw json from API as input
     * @param string $type: API type (e.g. quote)
     * @throws TransformerExceptions
     * 
     * @return object
     */
    public static function dataTransformation(string $result, string $type): object
    { 
        try {

            $data = (object) Transformer::decodeJsonResponse($result);

            QuoteValidator::validateResults($data);
           
            return Transformer::flattenData((array) $data->$type->result[0]);

        } catch (\Exception $e) {

            throw new TransformerExceptions("Error transform Quote Date."); 

        }
    }

    /**
     * Check for allowed fields (array), combine and return as string
     * 
     * @param mixed[] $fields: Input Fields
     * @param mixed[] $allowedFields: Allowed Fields input
     * 
     * @throws TransformerExceptions
     * 
     * @return string: a string with all fields (for use in API request)
     */
    public static function transformFields(array $fields, array $allowedFields): string
    {   
        $validFields = "";

        if (!empty($fields) && is_array($fields)) {

            foreach ( $fields as $field) {
                if (in_array($field, $allowedFields)) {
                    $validFields = $validFields . $field . ",";
                } else {
                    throw new TransformerExceptions("Transformation failed");
                }
            }
        }
        // remove the last ","
        return substr($validFields, 0, -1);
    }

    /**
     * Return Quote as Object
     * 
     * @param string $data: raw json data as input
     * 
     * @return object: converted Quote Data as Output
     */
    public static function returnQuoteAsObj(string $data): object
    {
        return QuoteTransformer::dataTransformation($data, "quoteResponse"); 
    }

    /**
     * Return Quote as Array
     * 
     * @param string $data raw json data as input
     * 
     * @return mixed[]: converted Quote Data as Output
     */
    public static function returnQuoteAsArray(string $data): array
    {
        $output = self::returnQuoteAsObj($data);

        return (array) $output; 
    }

    /** 
     * Return Quote in specified Output Format
     * 
     * @param string $data: raw json as input
     * @param string $format: either raw (string), object or array
     * 
     * @throws TransformerExceptions
     * 
     * @return mixed[]: Return converted and formatted data
     */
    public static function output(string $data, string $format): string | object | array
    {
        switch($format){
        case "raw":
            return $data;
        case "object":
            return self::returnQuoteAsObj($data);
        case "array":
            return self::returnQuoteAsArray($data);
        default:
            throw new TransformerExceptions("Output Format invalid");
        }
    }
}
