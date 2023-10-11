<?php declare(strict_types=1);
/**
 * Copyright 2023 Dominic Kneup.
 *
 * Licensed under the MIT License, you find the LICENSE file in the projects 
 * root folder; you may not use this file except in compliance with the License.
 */

use yfAPI\API\HistoricData;
use PHPUnit\Framework\TestCase;


class HistoricDataTest extends TestCase
{   
    public $historicData;

    public function setUp(): void
    {
        parent::setUp();
        
        $getHistoricData = new HistoricData();
        $getHistoricData->output = "raw"; // Set output format to raw (json text string)

        $symbol = "AMD";
        $startDate = new \DateTime("first day of this week");
        $endDate =  new \DateTime("today");

        // Get a new Crumb
        $this->historicData = $getHistoricData->getHistoricData($symbol, $startDate, $endDate);
    }

    public function testGetHistoricDataNotNull() {
        $this->assertNotNull($this->historicData);
    }

    public function testGetHistoricalDataIsString(){
        $this->assertIsString($this->historicData);
    }
}