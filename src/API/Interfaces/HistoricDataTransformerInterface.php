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
 * Interface HistoricDataTransformerInterface
 * 
 * @category Interface
 * @package  42dknp/yfapi-php
 * @author   Dominic Kneup
 * @license  MIT License
 * @link     https://github.com/42dknp/yfapi-php
 */
interface HistoricDataTransformerInterface
{

    /**
     * Transform Results  
     *
     * @param string $result
     * 
     * @throws TransformerExceptions
     * @return mixed[]
     */
    public static function transformResults(string $result, string $output): array;

    /**
     * Transform Single Result Data 
     *
     * @param $timestamp
     * @param float $open
     * @param float $low
     * @param float $high
     * @param float $close
     * @param float $adjClose
     * 
     * @return mixed[]
     */
    public static function transformSimgleData(int $timestamp, float $open, float $low, float $high, float $close, float $adjClose, string $output): object | array;
    
    /**
     * Return Historic Data in specified Output Format
     * 
     * @param string $data
     * @param string $output
     * 
     * @throws TransformerExceptions
     * 
     * @return mixed[]
     */
    public static function output(string $data, string $output): string | object | array;
}