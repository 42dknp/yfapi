<?php declare(strict_types=1);
/**
 * Copyright 2023 Dominic Kneup.
 *
 * Licensed under the MIT License, you find the LICENSE file in the projects 
 * root folder; you may not use this file except in compliance with the License.
 */

use yfAPI\API\SimilarSecurities;
use PHPUnit\Framework\TestCase;


class SimilarSecuritiesTest extends TestCase
{  
    public $similarSecuritiesData;

    public function setUp(): void
    {
        parent::setUp();
        
        $getSimilarSecurities = new SimilarSecurities();
        $getSimilarSecurities->output = "raw"; // Set output format to raw (json text string)

        $symbol = "AAPL";

        // Get a new Crumb
        $this->similarSecuritiesData = $getSimilarSecurities->getSimilarSecurities($symbol);
    }

    public function testSimilarSecuritiesNotNull() {
        $this->assertNotNull($this->similarSecuritiesData);
    }
    public function testGSimilarSecuritiesIsString(){
        $this->assertIsString($this->similarSecuritiesData);
    }
}