<?php declare(strict_types=1);
/**
 * Copyright 2023 Dominic Kneup.
 *
 * Licensed under the MIT License, you find the LICENSE file in the projects 
 * root folder; you may not use this file except in compliance with the License.
 */

use yfAPI\API\Validators\SearchValidator;
use yfAPI\Exceptions\ValidatorExceptions;
use PHPUnit\Framework\TestCase;

class SearchValidatorTest extends TestCase
{
    public $json;

    public function setUp(): void
    {
        // raw data from response
        $this->json = '{"explains":[],"count":7,"quotes":[{"exchange":"NYQ","shortname":"Eaton Vance High Yield","quoteType":"ETF","symbol":"EVHY","index":"quotes","score":100285.0,"typeDisp":"ETF","longname":"Morgan Stanley ETF Trust - Eaton Vance High Yield ETF","exchDisp":"NYSE","isYahooFinance":true},{"exchange":"NYQ","shortname":"Parametric Equity Premium Incom","quoteType":"ETF","symbol":"PAPI","index":"quotes","score":100112.0,"typeDisp":"ETF","longname":"Morgan Stanley Etf Trust - Parametric Divid Premium Income Etf","exchDisp":"NYSE","isYahooFinance":true},{"exchange":"NYQ","shortname":"Parametric Hedged Equity","quoteType":"ETF","symbol":"PHEQ","index":"quotes","score":100095.0,"typeDisp":"ETF","longname":"Morgan Stanley ETF Trust - Parametric Hedged Equity ETF","exchDisp":"NYSE","isYahooFinance":true},{"exchange":"NYQ","shortname":"Eaton Vance Ultra-Short","quoteType":"ETF","symbol":"EVSB","index":"quotes","score":100037.0,"typeDisp":"ETF","longname":"Morgan Stanley ETF Trust - Eaton Vance Ultra-Short Income ETF","exchDisp":"NYSE","isYahooFinance":true},{"exchange":"NYQ","shortname":"Eaton Vance Intermediate Muni","quoteType":"ETF","symbol":"EVIM","index":"quotes","score":100036.0,"typeDisp":"ETF","longname":"Morgan Stanley ETF Trust - Eaton Vance Intermediate Municipal Income ETF","exchDisp":"NYSE","isYahooFinance":true},{"exchange":"NYQ","shortname":"JP Morgan Chase & Co.","quoteType":"EQUITY","symbol":"JPM","index":"quotes","score":49068.0,"typeDisp":"Equity","longname":"JPMorgan Chase & Co.","exchDisp":"NYSE","sector":"Financial Services","sectorDisp":"Financial Services","industry":"Banks—Diversified","industryDisp":"Banks—Diversified","isYahooFinance":true},{"exchange":"NYQ","shortname":"Morgan Stanley","quoteType":"EQUITY","symbol":"MS","index":"quotes","score":41412.0,"typeDisp":"Equity","longname":"Morgan Stanley","exchDisp":"NYSE","sector":"Financial Services","sectorDisp":"Financial Services","industry":"Capital Markets","industryDisp":"Capital Markets","isYahooFinance":true}],"news":[],"nav":[],"lists":[],"researchReports":[],"screenerFieldResults":[],"totalTime":44,"timeTakenForQuotes":435,"timeTakenForNews":0,"timeTakenForAlgowatchlist":400,"timeTakenForPredefinedScreener":400,"timeTakenForCrunchbase":0,"timeTakenForNav":400,"timeTakenForResearchReports":0,"timeTakenForScreenerField":0,"timeTakenForCulturalAssets":0}';
    }

    public function testValidJsonData()
    {
        // Transform the JSON data into an object
        $data = json_decode($this->json);

        // Validate the JSON data using the SearchValidator
        try {
            SearchValidator::validateResults($data);

            // If validation succeeds, no exception should be thrown
            $this->assertTrue(true);
        } catch (ValidatorExceptions $e) {
            // If validation fails, this will be caught here
            $this->fail('Validation failed: ' . $e->getMessage());
        }
    }

    public function testMissingRequiredProperty()
    {
        // Remove a required property to create an invalid JSON data
        $invalidJson = json_decode($this->json);
        unset($invalidJson->quotes[0]->exchange);

        // Validate the JSON data using the SearchValidator
        $this->expectException(ValidatorExceptions::class);
        SearchValidator::validateResults($invalidJson);
    }

    public function testMissingQuotesProperty()
    {
        // Remove the "quotes" property to create invalid JSON data
        $invalidJson = json_decode($this->json);
        unset($invalidJson->quotes);

        // Validate the JSON data using the SearchValidator
        $this->expectException(ValidatorExceptions::class);
        SearchValidator::validateResults($invalidJson);
    }
    public function testValidJsonDataMultipleQuotes()
    {
        // Create JSON data with multiple quotes
        $json = '{
            "explains":[],
            "count": 2,
            "quotes": [
                {
                    "exchange":"NYQ",
                    "shortname":"Eaton Vance High Yield",
                    "quoteType":"ETF",
                    "symbol":"EVHY",
                    "index":"quotes",
                    "score":100285.0,
                    "typeDisp":"ETF",
                    "longname":"Morgan Stanley ETF Trust - Eaton Vance High Yield ETF",
                    "exchDisp":"NYSE",
                    "isYahooFinance":true
                },
                {
                    "exchange":"NYQ",
                    "shortname":"Parametric Equity Premium Incom",
                    "quoteType":"ETF",
                    "symbol":"PAPI",
                    "index":"quotes",
                    "score":100112.0,
                    "typeDisp":"ETF",
                    "longname":"Morgan Stanley Etf Trust - Parametric Divid Premium Income Etf",
                    "exchDisp":"NYSE",
                    "isYahooFinance":true
                }
            ],
            "news":[],
            "nav":[],
            "lists":[],
            "researchReports":[],
            "screenerFieldResults":[],
            "totalTime":44,
            "timeTakenForQuotes":435,
            "timeTakenForNews":0
        }';

        // Transform the JSON data into an object
        $data = json_decode($json);

        // Validate the JSON data using the SearchValidator
        try {
            SearchValidator::validateResults($data);

            // If validation succeeds, no exception should be thrown
            $this->assertTrue(true);
        } catch (ValidatorExceptions $e) {
            // If validation fails, this will be caught here
            $this->fail('Validation failed: ' . $e->getMessage());
        }
    }

    public function testEmptyJsonData()
    {
        // Create empty JSON data
        $json = '{}';

        // Transform the JSON data into an object
        $data = json_decode($json);

        // Validate the JSON data using the SearchValidator
        $this->expectException(ValidatorExceptions::class);
        SearchValidator::validateResults($data);
    }

    public function testValidJsonString()
    {
        $json = '{"explains":[],"count":5,"quotes":[{"exchange":"NMS","shortname":"Apple Inc.","quoteType":"EQUITY","symbol":"AAPL","index":"quotes","score":592636.0,"typeDisp":"Equity","longname":"Apple Inc.","exchDisp":"NASDAQ","sector":"Technology","sectorDisp":"Technology","industry":"Consumer Electronics","industryDisp":"Consumer Electronics","dispSecIndFlag":true,"isYahooFinance":true},{"exchange":"NYQ","shortname":"Apple Hospitality REIT, Inc.","quoteType":"EQUITY","symbol":"APLE","index":"quotes","score":21804.0,"typeDisp":"Equity","longname":"Apple Hospitality REIT, Inc.","exchDisp":"NYSE","sector":"Real Estate","sectorDisp":"Real Estate","industry":"REIT—Hotel & Motel","industryDisp":"REIT—Hotel & Motel","isYahooFinance":true},{"exchange":"NEO","shortname":"APPLE CDR (CAD HEDGED)","quoteType":"EQUITY","symbol":"AAPL.NE","index":"quotes","score":20595.0,"typeDisp":"Equity","longname":"Apple Inc.","exchDisp":"NEO","sector":"Technology","sectorDisp":"Technology","industry":"Consumer Electronics","industryDisp":"Consumer Electronics","isYahooFinance":true},{"exchange":"PNK","shortname":"APPLE RUSH COMPANY INC","quoteType":"EQUITY","symbol":"APRU","index":"quotes","score":20173.0,"typeDisp":"Equity","longname":"Apple Rush Company, Inc.","exchDisp":"OTC Markets","sector":"Consumer Defensive","sectorDisp":"Consumer Defensive","industry":"Beverages—Non-Alcoholic","industryDisp":"Beverages—Non—Alcoholic","isYahooFinance":true},{"exchange":"GER","shortname":"APPLE INC.","quoteType":"EQUITY","symbol":"APC.DE","index":"quotes","score":20162.0,"typeDisp":"Equity","longname":"Apple Inc.","exchDisp":"XETRA","sector":"Technology","sectorDisp":"Technology","industry":"Consumer Electronics","industryDisp":"Consumer Electronics","isYahooFinance":true}],"news":[],"nav":[],"lists":[],"researchReports":[],"screenerFieldResults":[],"totalTime":45,"timeTakenForQuotes":437,"timeTakenForNews":0,"timeTakenForAlgowatchlist":400,"timeTakenForPredefinedScreener":400,"timeTakenForCrunchbase":0,"timeTakenForNav":400,"timeTakenForResearchReports":0,"timeTakenForScreenerField":0,"timeTakenForCulturalAssets":0}';
        
        $data = json_decode($json);
        SearchValidator::validateResults($data);
        $this->assertTrue(true);
    }
}