<?php declare(strict_types=1);
/**
 * Copyright 2023 Dominic Kneup.
 *
 * Licensed under the MIT License, you find the LICENSE file in the projects 
 * root folder; you may not use this file except in compliance with the License.
 */

namespace yfAPI\API\Interfaces;

use yfAPI\Exceptions\TransformerExceptions;

interface SearchTransformerInterface
{

   
    /**
     * Transform API Results
     * 
     * @param string $response: Raw json string as input
     * 
     * @return mixed[]
     */
    public static function dataTransformation(string $response): array;

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
    public static function output(string $data, string $format): string | array | object;
}
