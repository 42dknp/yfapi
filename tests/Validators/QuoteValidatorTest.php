<?php declare(strict_types=1);
/**
 * Copyright 2023 Dominic Kneup.
 *
 * Licensed under the MIT License, you find the LICENSE file in the projects 
 * root folder; you may not use this file except in compliance with the License.
 */

use yfAPI\API\Validators\QuoteValidator;
use PHPUnit\Framework\TestCase;

class QuoteValidatorTest extends TestCase
{   
    public object $dataObject;

    public function setUp(): void
    {
        parent::setUp();

        // Original Raw API Output
        $data = '{"quoteResponse":{"result":[{"fullExchangeName":"NasdaqGS","symbol":"AAPL","fiftyTwoWeekLowChangePercent":{"raw":0.42941135,"fmt":"42.94%"},"gmtOffSetMilliseconds":-14400000,"regularMarketOpen":{"raw":173.8,"fmt":"173.80"},"language":"en-US","regularMarketTime":{"raw":1696622402,"fmt":"4:00PM EDT"},"regularMarketChangePercent":{"raw":1.4750453,"fmt":"1.48%"},"uuid":"8b10e4ae-9eeb-3684-921a-9ab27e4d87aa","quoteType":"EQUITY","regularMarketDayRange":{"raw":"173.18 - 177.99","fmt":"173.18 - 177.99"},"fiftyTwoWeekLowChange":{"raw":53.320007,"fmt":"53.32"},"fiftyTwoWeekHighChangePercent":{"raw":-0.104625896,"fmt":"-10.46%"},"regularMarketDayHigh":{"raw":177.99,"fmt":"177.99"},"typeDisp":"Equity","tradeable":false,"currency":"USD","sharesOutstanding":{"raw":15634199552,"fmt":"15.634B","longFmt":"15,634,199,552"},"fiftyTwoWeekHigh":{"raw":198.23,"fmt":"198.23"},"regularMarketPreviousClose":{"raw":174.91,"fmt":"174.91"},"exchangeTimezoneName":"America/New_York","fiftyTwoWeekHighChange":{"raw":-20.73999,"fmt":"-20.74"},"marketCap":{"raw":2774914039808,"fmt":"2.775T","longFmt":"2,774,914,039,808"},"regularMarketChange":{"raw":2.5800018,"fmt":"2.58"},"fiftyTwoWeekRange":{"raw":"124.17 - 198.23","fmt":"124.17 - 198.23"},"cryptoTradeable":false,"exchangeDataDelayedBy":0,"firstTradeDateMilliseconds":345479400000,"exchangeTimezoneShortName":"EDT","fiftyTwoWeekLow":{"raw":124.17,"fmt":"124.17"},"customPriceAlertConfidence":"HIGH","regularMarketPrice":{"raw":177.49,"fmt":"177.49"},"marketState":"PRE","regularMarketVolume":{"raw":57266675,"fmt":"57.267M","longFmt":"57,266,675"},"market":"us_market","quoteSourceName":"Delayed Quote","messageBoardId":"finmb_24937","priceHint":2,"regularMarketDayLow":{"raw":173.18,"fmt":"173.18"},"exchange":"NMS","sourceInterval":15,"shortName":"Apple Inc.","region":"US","triggerable":true,"corporateActions":[],"longName":"Apple Inc."}],"error":null}}';
        
        // Convert json string to object for further use
        $this->dataObject = json_decode($data);
    }
    
    public function testMapDataToObject()
    {        
        $this->assertNotFalse(QuoteValidator::validateResults($this->dataObject));
    }

    public function testValidData()
    {
        // No modifications needed for valid data test
        $this->assertNull(QuoteValidator::validateResults($this->dataObject));
    }

    public function testAdditionalSubProperties()
    {
        // Check if the property exists, if not, create it
        if (!isset($this->dataObject->quoteResponse->result[0]->fiftyTwoWeekLowChange)) {
            $this->dataObject->quoteResponse->result[0]->fiftyTwoWeekLowChange = (object)[];
        }
        $this->assertNull(QuoteValidator::validateResults($this->dataObject));
    }

    public function testMissingRequiredProperty()
    {
        $dataObject = $this->dataObject;
        $dataObject->quoteResponse->result[0]->symbol = null;

        $this->expectExceptionMessage("Missing symbol property");
        QuoteValidator::validateResults($this->dataObject);
    }

    public function testMissingSubProperties()
    {
        $dataObject = $this->dataObject;
        $dataObject->quoteResponse->result[0]->fiftyTwoWeekLowChange = (object) [];

        $this->expectExceptionMessage("Invalid sub-properties for fiftyTwoWeekLowChange");
        QuoteValidator::validateResults($this->dataObject);
    }

    public function testInvalidSubProperty()
    {   
        $dataObject = $this->dataObject;
        $dataObject->quoteResponse->result[0]->regularMarketDayLow = (object) ["invalidProperty" => 'invalid'];

        $this->expectExceptionMessage("Invalid sub-properties for regularMarketDayLow");
        QuoteValidator::validateResults($this->dataObject);
    }
}