<?php declare(strict_types=1);
/**
 * Copyright 2023 Dominic Kneup.
 *
 * Licensed under the MIT License, you find the LICENSE file in the projects 
 * root folder; you may not use this file except in compliance with the License.
 */

namespace yfAPI\API\Transformers;

use yfAPI\API\Interfaces\TransformerInterface;
use yfAPI\Exceptions\ValidatorExceptions;

/**
 * Class Transformer
 *
 * @category Transformer
 * @package  42dknp/yfapi-php
 * @author   Dominic Kneup
 * @license  MIT License
 * @link     https://github.com/42dknp/yfapi-php
 */
class Transformer implements TransformerInterface
{
     /**
      * Convert string to array
      * 
      * @param string $response: raw json string as input
      * 
      * @throws ValidatorExceptions
      * 
      * @return mixed[<mixed>]: returns an object
      */
    public static function decodeJsonResponse(string $response): mixed
    {
        $responseData = json_decode($response);
        
        if (!$responseData) {
            throw new ValidatorExceptions('Invalid JSON');
        }

        return $responseData;
    }

    /**
     * Flattens Multi-dimensional array into a 1-dimensional one
     * 
     * @param mixed[] $data: Multi-dimensional array
     * 
     * @return object: Return a "flat" Object
     */
    public static function flattenData(array $data): object
    {
        $object = new \stdClass(); // Create a generic object

        foreach ($data as $key => $value) {

            if (is_object($value) && isset($value->raw)) {

                $object->$key = $value->raw;

            } elseif (is_string($value)) {

                $object->$key = $value;
                
            }
        }
        return $object; 
    }
}