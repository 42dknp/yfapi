<?php declare(strict_types=1);
/**
 * Copyright 2023 Dominic Kneup.
 *
 * Licensed under the MIT License, you find the LICENSE file in the projects 
 * root folder; you may not use this file except in compliance with the License.
 */

namespace yfAPI\API\Validators;

use yfAPI\API\Interfaces\SearchValidatorInterface;
use yfAPI\Exceptions\ValidatorExceptions;
 
class SearchValidator implements SearchValidatorInterface
{
    /**
     * Define required Properties to check
     * 
     * @var string[] $requiredProperties
     */
    public static array $requiredProperties = [
        "exchange",
        "shortname",
        "quoteType",
        "symbol",
        "index",
        "score",
        "typeDisp",
        "longname",
        "exchDisp",
        "isYahooFinance"
    ];

    /**
     * * @param  object $data: Sear     * @param object $data: Search Results as Object
     * ect
     *
     * @throws ValidatorExceptions
     * 
     * @return void: None
     */
    public static function validateResults(object $data): void
    {
        if (isset($data->quotes)) {
            $quotes = $data->quotes;
            if (is_array($quotes)) {
                foreach ($quotes as $quote) {
                    foreach (SearchValidator::$requiredProperties as $property) {
                        if (!isset($quote->$property)) {
                            throw new ValidatorExceptions('Missing ' . $property . ' property');
                        }
                    }
                }
            }
        } else {
            throw new ValidatorExceptions('Missing "quotes" property in the JSON response');
        }
    }
}
