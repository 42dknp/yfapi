<?php declare(strict_types=1);
/**
 * Copyright 2023 Dominic Kneup.
 *
 * Licensed under the MIT License, you find the LICENSE file in the projects 
 * root folder; you may not use this file except in compliance with the License.
 */

use yfAPI\API\Transformers\SimilarSecuritiesTransformer;
use PHPUnit\Framework\TestCase;
use yfAPI\Exceptions\TransformerExceptions;

class SimilarSecuritiesTransformerTest extends TestCase
{
    public function testTransform()
    {
        $json = '{"finance":{"result":[{"symbol":"AMD","recommendedSymbols":[{"symbol":"NVDA","score":0.279067},{"symbol":"TSLA","score":0.191081},{"symbol":"INTC","score":0.189413},{"symbol":"META","score":0.182947},{"symbol":"NFLX","score":0.181781}]}],"error":null}}';
        $expectedSymbols = ['NVDA', 'TSLA', 'INTC', 'META', 'NFLX'];

        $actualSymbols = SimilarSecuritiesTransformer::transform($json);

        $this->assertEquals($expectedSymbols, $actualSymbols);
    }

    public function testOutputRaw()
    {
        $json = '{"finance":{"result":[{"symbol":"AMD","recommendedSymbols":[{"symbol":"NVDA","score":0.279067},{"symbol":"TSLA","score":0.191081},{"symbol":"INTC","score":0.189413},{"symbol":"META","score":0.182947},{"symbol":"NFLX","score":0.181781}]}],"error":null}}';
        $format = "raw";

        $actualResult = SimilarSecuritiesTransformer::output($json, $format);

        $this->assertEquals($json, $actualResult);
    }

    public function testOutputArray()
    {
        $json = '{"finance":{"result":[{"symbol":"AMD","recommendedSymbols":[{"symbol":"NVDA","score":0.279067},{"symbol":"TSLA","score":0.191081},{"symbol":"INTC","score":0.189413},{"symbol":"META","score":0.182947},{"symbol":"NFLX","score":0.181781}]}],"error":null}}';
        $format = "array";
        $expectedSymbols = ['NVDA', 'TSLA', 'INTC', 'META', 'NFLX'];

        $actualResult = SimilarSecuritiesTransformer::output($json, $format);

        $this->assertEquals($expectedSymbols, $actualResult);
    }

    public function testOutputInvalidFormat()
    {
        $this->expectException(TransformerExceptions::class);
        $this->expectExceptionMessage("Output Format invalid");

        $json = '{"finance":{"result":[{"symbol":"AMD","recommendedSymbols":[{"symbol":"NVDA","score":0.279067},{"symbol":"TSLA","score":0.191081},{"symbol":"INTC","score":0.189413},{"symbol":"META","score":0.182947},{"symbol":"NFLX","score":0.181781}]}],"error":null}}';
        $format = "invalid";

        SimilarSecuritiesTransformer::output($json, $format);
    }
}
