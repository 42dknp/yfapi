<?php declare(strict_types=1);
/**
 * Copyright 2023 Dominic Kneup.
 *
 * Licensed under the MIT License, you find the LICENSE file in the projects 
 * root folder; you may not use this file except in compliance with the License.
 */

use yfAPI\API\Search;
use PHPUnit\Framework\TestCase;

class SearchTest extends TestCase
{
    public $searchResponse;

    /**
     * Summary of setUp
     * @return void
     */
    public function setUp(): void
    {
        $getSearchResults = new Search();
        $getSearchResults->output = "raw"; # raw json output

        $searchTerm = "Microsoft";

        # Search for $searchTerm
        $this->searchResponse = $getSearchResults->searchFor($searchTerm); 
    }
    public function testSearchNotNull() {
        $this->assertNotNull($this->searchResponse);
    }
    public function testSearchIsString(){
        $this->assertIsString($this->searchResponse);
    }
}