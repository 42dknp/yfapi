<?php
/**
 * Copyright 2023 Dominic Kneup.
 *
 * Licensed under the MIT License, you find the LICENSE file in the projects 
 * root folder; you may not use this file except in compliance with the License.
 */

use yfAPI\API\Validators\HistoricDataValidator;
use yfAPI\Exceptions\ValidatorExceptions;
use PHPUnit\Framework\TestCase;

class HistoricDataValidatorTest extends TestCase
{   
    public object $dataObject;

    public function setUp(): void
    {
        parent::setUp();

        // Original Raw API Output
        $data = '{"chart":{"result":[{"meta":{"currency":"USD","symbol":"GS","exchangeName":"NYQ","instrumentType":"EQUITY","firstTradeDate":925824600,"regularMarketTime":1696881602,"gmtoffset":-14400,"timezone":"EDT","exchangeTimezoneName":"America/New_York","regularMarketPrice":312.61,"chartPreviousClose":323.57,"priceHint":2,"currentTradingPeriod":{"pre":{"timezone":"EDT","start":1696924800,"end":1696944600,"gmtoffset":-14400},"regular":{"timezone":"EDT","start":1696944600,"end":1696968000,"gmtoffset":-14400},"post":{"timezone":"EDT","start":1696968000,"end":1696982400,"gmtoffset":-14400}},"dataGranularity":"1d","range":"","validRanges":["1d","5d","1mo","3mo","6mo","1y","2y","5y","10y","ytd","max"]},"timestamp":[1696253400,1696339800,1696426200,1696512600,1696599000,1696858200],"indicators":{"quote":[{"low":[317.1000061035156,304.3900146484375,303.4800109863281,304.2099914550781,307.1700134277344,308.3699951171875],"volume":[1303800,3118600,1872000,1584600,1595100,1094400],"open":[322.0299987792969,315.2699890136719,304.8500061035156,307.3599853515625,308.1099853515625,308.8999938964844],"close":[318.5,306.1199951171875,308.6000061035156,310.5,312.4800109863281,312.6099853515625],"high":[323.5799865722656,315.67999267578125,309.05999755859375,310.54998779296875,315.32000732421875,313.4800109863281]}],"adjclose":[{"adjclose":[318.5,306.1199951171875,308.6000061035156,310.5,312.4800109863281,312.6099853515625]}]}}],"error":null}}';
        
        // Convert json string to object for further use
        $this->dataObject = json_decode($data);
    }

    public function testMapDataToObject()
    {      
        $this->assertNotFalse(HistoricDataValidator::validateResults($this->dataObject));
    }

    public function testValidateResults()
    {
        $data = $this->dataObject;
        
        // Test for missing data
        $dataMissing = $data;
        unset($dataMissing->chart->result[0]->meta->currency);

        $this->expectExceptionMessage('Missing currency property');
        HistoricDataValidator::validateResults($dataMissing);
        
        // Test for invalid data format
        $dataMissing = $data;
        $dataMissing->chart->result[0]->meta->timestamp = 'invalid format';

        $this->expectExceptionMessage('Missing timestamp format');
        HistoricDataValidator::validateResults($dataMissing);
    }

    public function testValidateCurrency(): void
    {
        $data = $this->dataObject;

        // Test for missing currency
        $dataMissingCurrency = $data;
        unset($dataMissingCurrency->chart->result[0]->meta->currency);
        $this->expectExceptionMessage('Missing currency property');
        HistoricDataValidator::validateResults($dataMissingCurrency);
    }

    public function testValidateSymbol(): void
    {
        $data = $this->dataObject;

        // Test for missing symbol
        $dataMissingSymbol = $data;
        unset($dataMissingSymbol->chart->result[0]->meta->symbol);
        $this->expectExceptionMessage('Missing symbol property');
        HistoricDataValidator::validateResults($dataMissingSymbol);
    }

    public function testValidateExchangeName(): void
    {
        $data = $this->dataObject;

        // Test for missing exchange name
        $dataMissingExchangeName = $data;
        unset($dataMissingExchangeName->chart->result[0]->meta->exchangeName);
        $this->expectExceptionMessage('Missing exchangeName property');
        HistoricDataValidator::validateResults($dataMissingExchangeName);
    }

    public function testValidatePrice(): void
    {
        $data = $this->dataObject;

        // Test for missing price
        $dataMissingPrice = $data;
        unset($dataMissingPrice->chart->result[0]->meta->regularMarketPrice);
        $this->expectExceptionMessage('Missing regularMarketPrice property');
        HistoricDataValidator::validateResults($dataMissingPrice);
    }

    public function testMissingMetaProperty(): void
    {
        $data = $this->dataObject;

        // Test for missing price
        $dataMissing = $data;
        unset($dataMissing->chart->result[0]->meta);
        $this->expectException(ValidatorExceptions::class);

        HistoricDataValidator::validateResults($dataMissing);
    }

    public function testMissingQuoteProperties()
    {
        $dataMissing = $this->dataObject;

        // Test for missing price
        unset($dataMissing->chart->result[0]->indicators->quote);
        $this->expectException(ValidatorExceptions::class);
        HistoricDataValidator::validateResults($dataMissing);
    }
    public function testValidateQuoteProperties()
    {
        $dataMissing = $this->dataObject;

        // Test for missing price
        unset($dataMissing->chart->result[0]->indicators->quote[0]->volume);
        $this->expectExceptionMessage('Invalid sub-properties for volume');
        HistoricDataValidator::validateResults($dataMissing);
    }
    public function testValidateAdjcloseProperties()
    {
        $dataMissing = $this->dataObject;

        // Test for missing adjclose
        unset($dataMissing->chart->result[0]->indicators->adjclose[0]);
        $this->expectExceptionMessage('Invalid sub-properties for adjclose');
        HistoricDataValidator::validateResults($dataMissing);
    }
}