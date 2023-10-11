<?php declare(strict_types=1);
/**
 * Copyright 2023 Dominic Kneup.
 *
 * Licensed under the MIT License, you find the LICENSE file in the projects 
 * root folder; you may not use this file except in compliance with the License.
 */

namespace yfAPI\API\Interfaces;

use yfAPI\Exceptions\TransformerExceptions;

/**
 * Interface SimilarSecuritiesTransformerInterface
 * 
 * @category Interface
 * @package  42dknp/yfapi-php
 * @author   Dominic Kneup
 * @license  MIT License
 * @link     https://github.com/42dknp/yfapi-php
 */
interface SimilarSecuritiesTransformerInterface
{

    /**
     * Transform Results
     * 
     * @param string $data
     * 
     * @throws TransformerExceptions
     * 
     * @return mixed[]
     */
    public static function transform(string $data): array;
    
    /**
     * Return Results as Array
     *
     * @param string $data
     * @param string $format
     * 
     * @throws TransformerExceptions
     * 
     * @return mixed[]
     */
    public static function output(string $data, string $format): array | string;
}