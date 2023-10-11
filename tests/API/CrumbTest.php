<?php declare(strict_types=1);
/**
 * Copyright 2023 Dominic Kneup.
 *
 * Licensed under the MIT License, you find the LICENSE file in the projects 
 * root folder; you may not use this file except in compliance with the License.
 */

use yfAPI\API\Crumb;
use PHPUnit\Framework\TestCase;

class CrumbTest extends TestCase
{   
    public $crumbData;

    public function setUp(): void
    {
        parent::setUp();
        // Setup
        $getCrumb = new Crumb();

        // Get a new Crumb
        $this->crumbData = $getCrumb->getCrumb();
    }

    public function testCrumbNotNull() {
        $this->assertNotNull($this->crumbData);
    }

    public function testCrumbIsString(){
        $this->assertIsString($this->crumbData);
    }
}