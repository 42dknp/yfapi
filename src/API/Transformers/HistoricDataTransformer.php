<?php declare(strict_types=1);
/**
 * Copyright 2023 Dominic Kneup.
 *
 * Licensed under the MIT License, you find the LICENSE file in the projects 
 * root folder; you may not use this file except in compliance with the License.
 */

namespace yfAPI\API\Transformers;

use yfAPI\API\Interfaces\HistoricDataTransformerInterface;
use yfAPI\API\Validators\HistoricDataValidator;
use yfAPI\API\Transformers\Transformer;
use yfAPI\Exceptions\TransformerExceptions;

/**
 * Class HistoricDataTransformer
 *
 * @category Transformer
 * @package  42dknp/yfapi-php
 * @author   Dominic Kneup
 * @license  MIT License
 * @link     https://github.com/42dknp/yfapi-php
 */
class HistoricDataTransformer implements HistoricDataTransformerInterface
{
    
    /**
     * Transform Results  
     *
     * @param string $result
     * 
     * @throws TransformerExceptions
     * @return mixed[]
     */
    public static function transformResults(string $result, string $output): array
    {
        try {
            $data = (object) Transformer::decodeJsonResponse($result);

            HistoricDataValidator::validateResults($data);

            $timestamps = $data->chart->result[0]->timestamp;
            $quoteData = $data->chart->result[0]->indicators->quote[0];
            $adjCloseData = $data->chart->result[0]->indicators->adjclose[0]->adjclose;
            
            $processedData = [];

            for ($i = 0; $i < count($timestamps); $i++) {

                if (!isset($quoteData->open[$i], $quoteData->low[$i], $quoteData->high[$i], $quoteData->close[$i], $adjCloseData[$i])) {
                    
                    continue; 

                }

                // Transform single results to object
                $processedData[] = self::transformSimgleData(
                    $timestamps[$i], 
                    $quoteData->open[$i], 
                    $quoteData->low[$i], 
                    $quoteData->high[$i], 
                    $quoteData->close[$i], 
                    $adjCloseData[$i],
                    $output
                );
            }

            return $processedData;

        } catch (\Exception $e) {
            throw new TransformerExceptions("Transformation failed."); 
        }
    }

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
    public static function transformSimgleData(int $timestamp, float $open, float $low, float $high, float $close, float $adjClose, string $output): object | array
    {
        $dataset = [
            'timestamp' => $timestamp,
            'open' => $open,
            'low' => $low,
            'high' => $high,
            'close' => $close,
            'adjclose' => $adjClose
        ];

        // if the output format should not be "array" (default) return as object
        return $output != "array" ? (object) $dataset : $dataset;
    }

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
    public static function output(string $data, string $output): string | object | array
    {   
        switch($output){
        case "raw": 
            return $data;
        case "array":
            return (array) self::transformResults($data, $output);
        case "object": 
            return (object) self::transformResults($data, $output);
        default:
            throw new TransformerExceptions("Output format invalid");
        }
    }
} 
