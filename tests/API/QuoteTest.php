<?php declare(strict_types=1);
/**
 * Copyright 2023 Dominic Kneup.
 *
 * Licensed under the MIT License, you find the LICENSE file in the projects 
 * root folder; you may not use this file except in compliance with the License.
 */

use yfAPI\API\Quote;
use PHPUnit\Framework\TestCase;


class QuoteTest extends TestCase
{  
    public $quoteData;

    public function setUp(): void
    {
        parent::setUp();
        
        $getQuote = new Quote();
        $getQuote->output = "raw"; // Set output format to raw (json text string)

        $symbol = "AAPL";

        // Get a new Crumb
        $this->quoteData = $getQuote->getQuote($symbol);
    }

    public function testQuoteNotNull() {
        $this->assertNotNull($this->quoteData);
    }

    public function testQuoteIsString(){
        $this->assertIsString($this->quoteData);
    }
}