<?php declare(strict_types=1);
/**
 * Copyright 2023 Dominic Kneup.
 *
 * Licensed under the MIT License, you find the LICENSE file in the projects 
 * root folder; you may not use this file except in compliance with the License.
 */

use yfAPI\API\Transformers\QuoteTransformer;
use PHPUnit\Framework\TestCase;

class QuoteTransformerTest extends TestCase
{
    private $json;

    public $expected;

    public function setUp(): void
    {
        // raw data from response
        $this->json = '{"quoteResponse":{"result":[{"fullExchangeName":"NasdaqGS","symbol":"AAPL","fiftyTwoWeekLowChangePercent":{"raw":0.42941135,"fmt":"42.94%"},"gmtOffSetMilliseconds":-14400000,"regularMarketOpen":{"raw":173.8,"fmt":"173.80"},"language":"en-US","regularMarketTime":{"raw":1696622402,"fmt":"4:00PM EDT"},"regularMarketChangePercent":{"raw":1.4750453,"fmt":"1.48%"},"uuid":"8b10e4ae-9eeb-3684-921a-9ab27e4d87aa","quoteType":"EQUITY","regularMarketDayRange":{"raw":"173.18 - 177.99","fmt":"173.18 - 177.99"},"fiftyTwoWeekLowChange":{"raw":53.320007,"fmt":"53.32"},"fiftyTwoWeekHighChangePercent":{"raw":-0.104625896,"fmt":"-10.46%"},"regularMarketDayHigh":{"raw":177.99,"fmt":"177.99"},"typeDisp":"Equity","tradeable":false,"currency":"USD","sharesOutstanding":{"raw":15634199552,"fmt":"15.634B","longFmt":"15,634,199,552"},"fiftyTwoWeekHigh":{"raw":198.23,"fmt":"198.23"},"regularMarketPreviousClose":{"raw":174.91,"fmt":"174.91"},"exchangeTimezoneName":"America/New_York","fiftyTwoWeekHighChange":{"raw":-20.73999,"fmt":"-20.74"},"marketCap":{"raw":2774914039808,"fmt":"2.775T","longFmt":"2,774,914,039,808"},"regularMarketChange":{"raw":2.5800018,"fmt":"2.58"},"fiftyTwoWeekRange":{"raw":"124.17 - 198.23","fmt":"124.17 - 198.23"},"cryptoTradeable":false,"exchangeDataDelayedBy":0,"firstTradeDateMilliseconds":345479400000,"exchangeTimezoneShortName":"EDT","fiftyTwoWeekLow":{"raw":124.17,"fmt":"124.17"},"customPriceAlertConfidence":"HIGH","regularMarketPrice":{"raw":177.49,"fmt":"177.49"},"marketState":"PRE","regularMarketVolume":{"raw":57266675,"fmt":"57.267M","longFmt":"57,266,675"},"market":"us_market","quoteSourceName":"Delayed Quote","messageBoardId":"finmb_24937","priceHint":2,"regularMarketDayLow":{"raw":173.18,"fmt":"173.18"},"exchange":"NMS","sourceInterval":15,"shortName":"Apple Inc.","region":"US","triggerable":true,"corporateActions":[],"longName":"Apple Inc."}],"error":null}}';
        
        // Array we expect as output
        $this->expected = [
            'fullExchangeName' => 'NasdaqGS',
            'symbol' => 'AAPL',
            'language' => 'en-US',
            'uuid' => '8b10e4ae-9eeb-3684-921a-9ab27e4d87aa',
            'quoteType' => 'EQUITY',
            'typeDisp' => 'Equity',
            'currency' => 'USD',
            'exchangeTimezoneName' => 'America/New_York',
            'exchangeTimezoneShortName' => 'EDT',
            'customPriceAlertConfidence' => 'HIGH',
            'marketState' => 'PRE',
            'market' => 'us_market',
            'quoteSourceName' => 'Delayed Quote',
            'messageBoardId' => 'finmb_24937',
            'exchange' => 'NMS',
            'shortName' => 'Apple Inc.',
            'region' => 'US',
            'longName' => 'Apple Inc.',
            'fiftyTwoWeekLowChangePercent' => 0.42941135,
            'regularMarketOpen' => 173.8,
            'regularMarketTime' => 1696622402,
            'regularMarketChangePercent' => 1.4750453,
            'regularMarketDayRange' => '173.18 - 177.99',
            'fiftyTwoWeekLowChange' => 53.320007,
            'fiftyTwoWeekHighChangePercent' => -0.104625896,
            'regularMarketDayHigh' => 177.99,
            'sharesOutstanding' => 15634199552,
            'fiftyTwoWeekHigh' => 198.23,
            'regularMarketPreviousClose' => 174.91,
            'fiftyTwoWeekHighChange' => -20.73999,
            'marketCap' => 2774914039808,
            'regularMarketChange' => 2.5800018,
            'fiftyTwoWeekRange' => '124.17 - 198.23',
            'fiftyTwoWeekLow' => 124.17,
            'regularMarketPrice' => 177.49,
            'regularMarketVolume' => 57266675,
            'regularMarketDayLow' => 173.18,
        ];
    }

    public function testTransform()
{

    $actualObj = QuoteTransformer::dataTransformation($this->json, 'quoteResponse');

    foreach ($this->expected as $key => $value) {
        $this->assertTrue(property_exists($actualObj, $key));
        $this->assertEquals($value, $actualObj->{$key});
    }
}


    public function testTransformFields()
    {
        $fields = ['symbol', 'regularMarketPrice'];
        $allowedFields = ['symbol', 'regularMarketPrice'];

        $expected = 'symbol,regularMarketPrice';

        $actual = QuoteTransformer::transformFields($fields, $allowedFields);

        $this->assertEquals($expected, $actual);
    }

    public function testReturnQuoteAsObj()
    {

        $actual = QuoteTransformer::returnQuoteAsObj($this->json);

        $this->assertEquals((object) $this->expected, $actual);
    }

    public function testReturnQuoteAsArray()
    {

        $actual = QuoteTransformer::returnQuoteAsArray($this->json);

        $this->assertEquals($this->expected, $actual);
    }

    public function testOutputInvalidFormat()
    {
        $this->expectExceptionMessage("Output Format invalid");

        QuoteTransformer::output($this->json, 'invalid');
    }
}
