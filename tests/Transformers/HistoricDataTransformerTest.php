<?php declare(strict_types=1);
/**
 * Copyright 2023 Dominic Kneup.
 *
 * Licensed under the MIT License, you find the LICENSE file in the projects 
 * root folder; you may not use this file except in compliance with the License.
 */

use yfAPI\API\Transformers\HistoricDataTransformer;
use PHPUnit\Framework\TestCase;

class HistoricDataTransformerTest extends TestCase
{
    private $json;

    public function setUp(): void
    {
        // raw data from response
        $this->json = '{"chart":{"result":[{"meta":{"currency":"USD","symbol":"GS","exchangeName":"NYQ","instrumentType":"EQUITY","firstTradeDate":925824600,"regularMarketTime":1696881602,"gmtoffset":-14400,"timezone":"EDT","exchangeTimezoneName":"America/New_York","regularMarketPrice":312.61,"chartPreviousClose":323.57,"priceHint":2,"currentTradingPeriod":{"pre":{"timezone":"EDT","start":1696924800,"end":1696944600,"gmtoffset":-14400},"regular":{"timezone":"EDT","start":1696944600,"end":1696968000,"gmtoffset":-14400},"post":{"timezone":"EDT","start":1696968000,"end":1696982400,"gmtoffset":-14400}},"dataGranularity":"1d","range":"","validRanges":["1d","5d","1mo","3mo","6mo","1y","2y","5y","10y","ytd","max"]},"timestamp":[1696253400,1696339800,1696426200,1696512600,1696599000,1696858200],"indicators":{"quote":[{"low":[317.1000061035156,304.3900146484375,303.4800109863281,304.2099914550781,307.1700134277344,308.3699951171875],"volume":[1303800,3118600,1872000,1584600,1595100,1094400],"open":[322.0299987792969,315.2699890136719,304.8500061035156,307.3599853515625,308.1099853515625,308.8999938964844],"close":[318.5,306.1199951171875,308.6000061035156,310.5,312.4800109863281,312.6099853515625],"high":[323.5799865722656,315.67999267578125,309.05999755859375,310.54998779296875,315.32000732421875,313.4800109863281]}],"adjclose":[{"adjclose":[318.5,306.1199951171875,308.6000061035156,310.5,312.4800109863281,312.6099853515625]}]}}],"error":null}}';
        
    }

    public function testTransformResults()
    {
        $expectedCount = 6; // Number of timestamps in the provided JSON

        $actual = HistoricDataTransformer::transformResults($this->json, 'array');

        $this->assertCount($expectedCount, $actual);

        // Ensure the transformed data contains expected keys
        $this->assertArrayHasKey('timestamp', $actual[0]);
        $this->assertArrayHasKey('open', $actual[0]);
        $this->assertArrayHasKey('low', $actual[0]);
        $this->assertArrayHasKey('high', $actual[0]);
        $this->assertArrayHasKey('close', $actual[0]);
        $this->assertArrayHasKey('adjclose', $actual[0]);
    }

    public function testTransformSingleData()
    {
        $timestamp = 1696253400;
        $open = 322.0299987792969;
        $low = 317.1000061035156;
        $high = 323.5799865722656;
        $close = 318.5;
        $adjClose = 318.5;

        $expectedArray = [
            'timestamp' => $timestamp,
            'open' => $open,
            'low' => $low,
            'high' => $high,
            'close' => $close,
            'adjclose' => $adjClose
        ];

        $expectedObject = (object)$expectedArray;

        // Test array output
        $actualArray = HistoricDataTransformer::transformSimgleData($timestamp, $open, $low, $high, $close, $adjClose, 'array');
        $this->assertEquals($expectedArray, $actualArray);

        // Test object output
        $actualObject = HistoricDataTransformer::transformSimgleData($timestamp, $open, $low, $high, $close, $adjClose, 'object');
        $this->assertEquals($expectedObject, $actualObject);
    }

    public function testOutput()
    {

        $expectedArrayOutput = [
            (object) [
                'timestamp' => 1696253400,
                'open' => 322.0299987792969,
                'low' => 317.1000061035156,
                'high' => 323.5799865722656,
                'close' => 318.5,
                'adjclose' => 318.5,
            ],
            (object) [
                'timestamp' => 1696339800,
                'open' => 315.2699890136719,
                'low' => 304.3900146484375,
                'high' => 315.67999267578125,
                'close' => 306.1199951171875,
                'adjclose' => 306.1199951171875,
            ],
            (object) [
                'timestamp' => 1696426200,
                'open' => 304.8500061035156,
                'low' => 303.4800109863281,
                'high' => 309.05999755859375,
                'close' => 308.6000061035156,
                'adjclose' => 308.6000061035156,
            ],
            (object) [
                'timestamp' => 1696512600,
                'open' => 307.3599853515625,
                'low' => 304.2099914550781,
                'high' => 310.54998779296875,
                'close' => 310.5,
                'adjclose' => 310.5,
            ],
            (object) [
                'timestamp' => 1696599000,
                'open' => 308.1099853515625,
                'low' => 307.1700134277344,
                'high' => 315.32000732421875,
                'close' => 312.4800109863281,
                'adjclose' => 312.4800109863281,
            ],
            (object) [
                'timestamp' => 1696858200,
                'open' => 308.8999938964844,
                'low' => 308.3699951171875,
                'high' => 313.4800109863281,
                'close' => 312.6099853515625,
                'adjclose' => 312.6099853515625,
            ],
        ];
        
        // Test specific array output
        $actualArrayOutput = HistoricDataTransformer::output($this->json, 'array');
        $this->assertEquals(1696253400, $actualArrayOutput[0]["timestamp"]);
        $this->assertEquals(303.4800109863281, $actualArrayOutput[2]["low"]);
        $this->assertEquals(312.4800109863281, $actualArrayOutput[4]["adjclose"]);
        $this->assertEquals(308.8999938964844, $actualArrayOutput[5]["open"]);
        
        // Test object output
        $actualObjectOutput = HistoricDataTransformer::output($this->json, 'object');
        $this->assertEquals($expectedArrayOutput, (array)$actualObjectOutput);
    }

}

