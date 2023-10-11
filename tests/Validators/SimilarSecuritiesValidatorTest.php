<?php
/**
 * Copyright 2023 Dominic Kneup.
 *
 * Licensed under the MIT License, you find the LICENSE file in the projects 
 * root folder; you may not use this file except in compliance with the License.
 */

use yfAPI\API\Validators\SimilarSecuritiesValidator;
use PHPUnit\Framework\TestCase;

class SimilarSecuritiesValidatorTest extends TestCase
{
    public object $dataObject;

    public function setUp(): void
    {
        parent::setUp();

        // Original Raw API Output for SimilarSecuritiesValidator
        $data = '{"finance":{"result":[{"symbol":"AMD","recommendedSymbols":[{"symbol":"NVDA","score":0.279067},{"symbol":"TSLA","score":0.191081},{"symbol":"INTC","score":0.189413},{"symbol":"META","score":0.182947},{"symbol":"NFLX","score":0.181781}]}],"error":null}}';

        // Convert json string to object for further use
        $this->dataObject = json_decode($data);
    }

    public function testMapDataToObject()
    {
        $this->assertNotFalse(SimilarSecuritiesValidator::validateResults($this->dataObject));
    }

    public function testValidateResults()
    {
        $data = $this->dataObject;

        // Test for missing data
        $dataMissing = $data;
        unset($dataMissing->finance->result[0]->recommendedSymbols);

        $this->expectExceptionMessage('Missing recommendedSymbols property');
        SimilarSecuritiesValidator::validateResults($dataMissing);
        
        // Test for invalid data format
        $dataInvalidFormat = $data;
        $dataInvalidFormat->finance->result[0]->recommendedSymbols[0]->symbol = 123; // Invalid symbol

        $this->expectExceptionMessage('Invalid sub-properties for symbol');
        SimilarSecuritiesValidator::validateResults($dataInvalidFormat);
    }

    public function testValidateSymbol(): void
    {
        $data = $this->dataObject;

        // Test for missing symbol
        $dataMissingSymbol = $data;
        unset($dataMissingSymbol->finance->result[0]->symbol);
        $this->expectExceptionMessage('Missing symbol property');
        SimilarSecuritiesValidator::validateResults($dataMissingSymbol);
    }

    public function testValidateRecommendedSymbols(): void
    {
        $data = $this->dataObject;

        // Test for missing recommendedSymbols
        $dataMissingRecommendedSymbols = $data;
        unset($dataMissingRecommendedSymbols->finance->result[0]->recommendedSymbols);
        $this->expectExceptionMessage('Missing recommendedSymbols property');
        SimilarSecuritiesValidator::validateResults($dataMissingRecommendedSymbols);
    }
}
