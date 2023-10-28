<?php declare(strict_types=1);
/**
 * Copyright 2023 Dominic Kneup.
 *
 * Licensed under the MIT License, you find the LICENSE file in the projects 
 * root folder; you may not use this file except in compliance with the License.
 */

namespace yfAPI\API\Transformers;

use yfAPI\API\Interfaces\SearchTransformerInterface;
use yfAPI\API\Transformers\Transformer;
use yfAPI\API\Validators\SearchValidator;
use yfAPI\Exceptions\TransformerExceptions;
 
class SearchTransformer implements SearchTransformerInterface
{
    /**
     * Transform API Results
     * 
     * @param string $response: Raw json string as input
     * 
     * @return mixed[]
     */
    public static function dataTransformation(string $response): array
    {
        try {

            // Decode the JSON string into a PHP array
            $data = (object) Transformer::decodeJsonResponse($response);

            // Validate Search Results
            SearchValidator::validateResults((object) $data);

            // Check if "quotes" key exists in the data
            return $data->quotes;
        }
        catch (\Exception $e) {
            throw new TransformerExceptions("Error transform Quote Date.");
        }
    }

    /**
     * Transform to full array
     * 
     * @param string $data
     * 
     * @return mixed[]
     */
    public static function transformArray(string $data): array
    {   
        $originalArray = self::dataTransformation($data);
        $convertedArray = array();

        foreach ($originalArray as $object) {
            $convertedArray[] = get_object_vars((object) $object);
        }

        return $convertedArray;
    }
    /**
     *  Return Search Results in specified Output Format
     * 
     * @param string $data:   Raw json string as input
     * @param string $format: either raw (string) or object
     * 
     * @throws TransformerExceptions
     * 
     * @return mixed[]: Return converted and formatted data
     */
    public static function output(string $data, string $format): string | array | object
    {
        switch($format){
        case "raw":
            return $data;
        case "array":
            return self::transformArray($data);
        case "object":
            return self::dataTransformation($data);
        default:
            throw new TransformerExceptions("Output Format invalid");
        }
    }
} 
