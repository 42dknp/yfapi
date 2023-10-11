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
 * Interface QuoteTransformerInterface
 * 
 * @category Interface
 * @package  42dknp/yfapi-php
 * @author   Dominic Kneup
 * @license  MIT License
 * @link     https://github.com/42dknp/yfapi-php
 */
interface QuoteTransformerInterface
{

    /**
     * Transform API result
     * 
     * @param string $result 
     * 
     * @throws TransformerExceptions
     * 
     * @return object
     */
    public static function dataTransformation(string $result, string $type): object;

    /**
     * Check for allowed fields (array), combine and return as string
     * 
     * @param mixed[] $fields
     * @param mixed[] $allowedFields
     * 
     * @throws TransformerExceptions
     * 
     * @return string
     */
    public static function transformFields(array $fields, array $allowedFields): string;

    /**
     * Return Quote as Object
     * 
     * @param string $data
     * 
     * @return object
     */
    public static function returnQuoteAsObj(string $data): object;

    /**
     * Return Quote as Array
     * 
     * @param string $data
     * 
     * @return mixed[]
     */
    public static function returnQuoteAsArray(string $data): array;

    /** 
     * Return Quote in specified Output Format
     * 
     * @param string $data
     * @param string $format
     * 
     * @throws TransformerExceptions
     * 
     * @return mixed[]
     */
    public static function output(string $data, string $format): string | object | array;
}