<?php
/**
 * Copyright 2023 Dominic Kneup.
 *
 * Licensed under the MIT License, you find the LICENSE file in the projects 
 * root folder; you may not use this file except in compliance with the License.
 */

use PHPUnit\Framework\TestCase;
use yfAPI\API\Validators\CrumbValidator;

class CrumbValidatorTest extends TestCase
{   

    // Test if method returns the value that was sent
    public function testValidCrumbSymbols()
    {
        // Test case 1: input has numbers, letters, and symbols
        $input1 = "abc123@#$";
        $this->assertSame($input1, CrumbValidator::validateCrumb($input1));
    }

    // Test if Exception is raised correctly
    public function testValidateCrumbReturnsErrorMessageWhenEmpty()
    {
        // Arrange
        $crumb = '';
        $this->expectException(Exception::class);
        // Act
        CrumbValidator::validateCrumb($crumb);

    }
}
