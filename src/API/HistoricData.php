<?php declare(strict_types=1);
/**
 * Copyright 2023 Dominic Kneup.
 *
 * Licensed under the MIT License, you find the LICENSE file in the projects 
 * root folder; you may not use this file except in compliance with the License.
 */

namespace yfAPI\API;

use yfAPI\APIClient;
use yfAPI\API\Crumb;
use yfAPI\API\Validators\Validator;
use yfAPI\API\Transformers\HistoricDataTransformer;

/**
 * Class HistoricData
 *
 * @category API
 * @package  42dknp/yfapi-php
 * @author   Dominic Kneup
 * @license  MIT License
 * @link     https://github.com/42dknp/yfapi-php
 */
class HistoricData extends APIClient
{

     /**
      * API Endpoint URL
      * 
      * @var string $endpoint
      */
    public string $endpoint = "https://query1.finance.yahoo.com/v8/finance/chart/";
    
    /**
     * Define interval 
     * 
     * @var string $interval
     */
    public string $interval = "1d";

    /**
     * Setup Default Output Format
     * 
     * @var string $output
     */
    public string $output = "object";

    /**
     * Define specific Crumb 
     * 
     * @var string $crumb
     */
    public string $crumb;

    /**
     * Get Historic Data API Call
     *
     * @param string             $stock
     * @param \DateTimeInterface $startDate
     * @param \DateTimeInterface $endDate
     * 
     * @throws \Exception
     * @return mixed[]
     */
    public function getHistoricData(string $stock, \DateTimeInterface $startDate, \DateTimeInterface $endDate): string | object | array 
    {   
        // Validate given parameters ($startDate, $endDate & $this->interval)
        if (Validator::checkInterval($this->interval) && Validator::validateDates($startDate, $endDate)) {

            // URL endpoint
            $url = $this->endpoint . $stock;

            // Check if $this->crumb was defined by user, get a new crumb if not
            if (empty($this->crumb)) : 
                $getCrumb = new Crumb();
                $this->crumb = $getCrumb->getCrumb();
            endif;

            // URL Parameter as array
            $params = [
                'period1' => $startDate->getTimestamp(),
                'period2' => $endDate->getTimestamp(),
                'interval' => $this->interval,
                'crumb' => $this->crumb
            ];

            $response = $this->requestAPI($url, $params);

            return HistoricDataTransformer::output($response, $this->output); 
        } else {
            throw new \Exception();
        }
    }

     /**
      * Get Historic Data for YTD
      *
      * @param string $symbol
      * 
      * @return mixed[]
      */
    public function getHistoricDataYTD(string $symbol): string | object | array 
    {
        return $this->getHistoricData(
            $symbol, 
            new \DateTime("first day of January this year"), 
            new \DateTime("today"), 
        );
    }

    /**
     * Get Historic Data for Last Year 
     *
     * @param string $symbol
     * 
     * @return mixed[]
     */
    public function getHistoricDataLastYear(string $symbol): string | object | array 
    {
        return $this->getHistoricData(
            $symbol, 
            new \DateTime("first day of January last year"), 
            new \DateTime("last day of December last year"), 
        );
    }

    /**
     * Get Historic Data for Last Month 
     *
     * @param string $symbol
     * 
     * @return mixed[]
     */
    public function getHistoricDataLastMonth(string $symbol): string | object | array 
    {
        return $this->getHistoricData(
            $symbol, 
            new \DateTime("first day of last month"), 
            new \DateTime("last day of last month"), 
        );
    }

    /**
     * Get Historic Data for Last Week 
     *
     * @param string $symbol
     * 
     * @return mixed[]
     */
    public function getHistoricDataLastWeek(string $symbol): string | object | array 
    {
        return $this->getHistoricData(
            $symbol, 
            new \DateTime("last week monday"), 
            new \DateTime("last week sunday"),
        );
    }
}
