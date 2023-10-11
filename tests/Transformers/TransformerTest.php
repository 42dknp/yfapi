<?php declare(strict_types=1);
/**
 * Copyright 2023 Dominic Kneup.
 *
 * Licensed under the MIT License, you find the LICENSE file in the projects 
 * root folder; you may not use this file except in compliance with the License.
 */

use yfAPI\API\Transformers\Transformer;
use yfAPI\Exceptions\ValidatorExceptions;
use PHPUnit\Framework\TestCase;

class TransformerTest extends TestCase
{      
    public object $dataObject;

    public function setUp(): void
    {
        parent::setUp();

        // Original Raw API Output
        $data = '{"quoteResponse":{"result":[{"fullExchangeName":"NasdaqGS","symbol":"AMD","fiftyTwoWeekLowChangePercent":{"raw":0.9931281,"fmt":"99.31%"},"gmtOffSetMilliseconds":-14400000,"regularMarketOpen":{"raw":109.14,"fmt":"109.14"},"language":"en-US","regularMarketTime":{"raw":1697035260,"fmt":"10:41AM EDT"},"regularMarketChangePercent":{"raw":-0.22475255,"fmt":"-0.22%"},"uuid":"48af4341-f745-363f-945f-a838eeabb062","quoteType":"EQUITY","regularMarketDayRange":{"raw":"107.89 - 110.1","fmt":"107.89 - 110.10"},"fiftyTwoWeekLowChange":{"raw":54.195,"fmt":"54.19"},"fiftyTwoWeekHighChangePercent":{"raw":-0.18117143,"fmt":"-18.12%"},"regularMarketDayHigh":{"raw":110.1,"fmt":"110.10"},"typeDisp":"Equity","tradeable":false,"currency":"USD","sharesOutstanding":{"raw":1615670016,"fmt":"1.616B","longFmt":"1,615,670,016"},"fiftyTwoWeekHigh":{"raw":132.83,"fmt":"132.83"},"regularMarketPreviousClose":{"raw":109.01,"fmt":"109.01"},"exchangeTimezoneName":"America/New_York","fiftyTwoWeekHighChange":{"raw":-24.065002,"fmt":"-24.07"},"marketCap":{"raw":175728345088,"fmt":"175.728B","longFmt":"175,728,345,088"},"regularMarketChange":{"raw":-0.24500275,"fmt":"-0.25"},"fiftyTwoWeekRange":{"raw":"54.57 - 132.83","fmt":"54.57 - 132.83"},"cryptoTradeable":false,"exchangeDataDelayedBy":0,"firstTradeDateMilliseconds":322151400000,"exchangeTimezoneShortName":"EDT","regularMarketPrice":{"raw":108.765,"fmt":"108.76"},"fiftyTwoWeekLow":{"raw":54.57,"fmt":"54.57"},"marketState":"REGULAR","customPriceAlertConfidence":"HIGH","market":"us_market","regularMarketVolume":{"raw":14020928,"fmt":"14.021M","longFmt":"14,020,928"},"quoteSourceName":"Nasdaq Real Time Price","messageBoardId":"finmb_168864","priceHint":2,"regularMarketDayLow":{"raw":107.89,"fmt":"107.89"},"exchange":"NMS","sourceInterval":15,"region":"US","shortName":"Advanced Micro Devices, Inc.","triggerable":true,"corporateActions":[],"longName":"Advanced Micro Devices, Inc."}],"error":null}}';

        // Convert json string to object for further use
        $this->dataObject = (object) json_decode($data);
    }

    public function testDecodeJsonResponseValidJson(): void
    {
        $validJson = '{"fullExchangeName": "NasdaqGS"}';

        $decodedData = Transformer::decodeJsonResponse($validJson);

        // Expected value should be an object
        $expectedData = (object) ['fullExchangeName' => 'NasdaqGS'];

        $this->assertEquals($expectedData, $decodedData);
    }

    public function testDecodeJsonResponseInvalidJson(): void
    {
        $invalidJson = '{"key": "value",}'; // Invalid JSON due to trailing comma

        $this->expectException(ValidatorExceptions::class);
        Transformer::decodeJsonResponse($invalidJson);
    }

    public function testDecodeJsonResponseEmptyJson(): void
    {
        $emptyJson = '';

        $this->expectExceptionMessage("Invalid JSON");

        Transformer::decodeJsonResponse($emptyJson);
    }

    public function testflatenDataWithStringValue(): void
    {
        $data = [
            'fullExchangeName' => $this->dataObject->quoteResponse->result[0]->fullExchangeName,
            'exchangeTimezoneShortName' => $this->dataObject->quoteResponse->result[0]->exchangeTimezoneShortName,
        ];

        $flattenedData = Transformer::flattenData($data);

        $expectedData = (object) [
            'fullExchangeName' => 'NasdaqGS',
            'exchangeTimezoneShortName' => "EDT",
        ];

        $this->assertEquals($expectedData, $flattenedData);
    }

    public function voidtestflatenDataWithRawValue(): void
    {
        $data = [
            'fullExchangeName' => $this->dataObject->quoteResponse->result[0]->fullExchangeName,
            'fiftyTwoWeekLowChangePercent' =>  (object) ["raw" => $this->dataObject->quoteResponse->result[0]->fiftyTwoWeekLowChangePercent->raw],
        ];

        $flattenedData = Transformer::flattenData($data);

        $expectedData = (object) [
            'fullExchangeName' => $this->dataObject->quoteResponse->result[0]->fullExchangeName,
            'fiftyTwoWeekLowChangePercent' => $this->dataObject->quoteResponse->result[0]->fiftyTwoWeekLowChangePercent->raw,
        ];

        $this->assertEquals($expectedData, $flattenedData);
    }

    public function testFlatenDataWithMixedValues(): void
    {
        $data = [
            'symbol' => $this->dataObject->quoteResponse->result[0]->symbol,
            'language' => $this->dataObject->quoteResponse->result[0]->language,
            'fiftyTwoWeekLowChangePercent' =>  (object) ["raw" => $this->dataObject->quoteResponse->result[0]->fiftyTwoWeekLowChangePercent->raw],
            'regularMarketDayLow' =>  (object) ["raw" => $this->dataObject->quoteResponse->result[0]->regularMarketDayLow->raw],
        ];

        // Convert stdClass properties to an array for the test
        $flattenedData = Transformer::flattenData((array) $data);

        $expectedData = (object) [
            'symbol' => $this->dataObject->quoteResponse->result[0]->symbol,
            'language' => $this->dataObject->quoteResponse->result[0]->language,
            'fiftyTwoWeekLowChangePercent' => $this->dataObject->quoteResponse->result[0]->fiftyTwoWeekLowChangePercent->raw,
            'regularMarketDayLow' => $this->dataObject->quoteResponse->result[0]->regularMarketDayLow->raw,
        ];

        $this->assertEquals($expectedData, $flattenedData);
    }

    public function testflatenDataWithEmptyArray(): void
    {
        $data = [];

        $flattenedData = Transformer::flattenData($data);

        $this->assertInstanceOf(\stdClass::class, $flattenedData);
        $this->assertEmpty((array) $flattenedData);
    }

}