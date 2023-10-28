<?php declare(strict_types=1);
/**
 * Copyright 2023 Dominic Kneup.
 *
 * Licensed under the MIT License, you find the LICENSE file in the projects 
 * root folder; you may not use this file except in compliance with the License.
 */

namespace yfAPI;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;

use yfAPI\API\Validators\Validator;
use yfAPI\API\Interfaces\APIClientInterface;
use yfAPI\Exceptions\APIClientException;

/**
 * Class APIClient
 * 
 * @category API
 * @package  yfapi-php
 * @author   Dominic Kneup
 * @license  MIT License
 * @link     https://github.com/42dknp/yfapi-php
 */
class APIClient implements APIClientInterface
{
   
    /** 
     * Setup Client 
     * 
     * @var Client $httpClient
     */
    public Client $httpClient;

    /** 
     * Setup CookieJar 
     * 
     * @var CookieJar $cookieJar
     */
    public CookieJar $cookieJar;

    /** 
     * Store Raw Response in Attribute
     * 
     * @var string $requestContent
     */
    public string $requestContent;
    
    /**
     * Class Constructor creates an Instance for both, CookieJar() and Client()
     */
    public function __construct()
    {
        $this->cookieJar = new CookieJar();
        $this->httpClient = new Client(); 
    }

    /**
     * Send GET request with $url to endpoint and return answer
     * 
     * @param string            $url     : The URL for the API request.
     * @param array<string|int> $params: The parameters for the API request.
     * 
     * @throws RequestException
     * @throws ConnectException
     * 
     * @return string: The response from the API as a string.
     */
    public function requestAPI(string $url, array $params = []): string
    {
        if (Validator::validURL($url)) { 
            try 
            {
                $response = $this->httpClient->request(
                    'GET', $url, [
                    'cookies' => $this->cookieJar,
                    'headers' => ["User-Agent" => $this->getRandomUserAgent()],
                    'query' => $params
                    ]
                );

                // Write content in attribute $requestContent for 
                // possible further use (Custom Validations etc.)
                $this->requestContent = $response->getBody()->getContents();
            } 
            catch (RequestException $e) 
            {
                return $e->getMessage();
            }
            catch (ConnectException $e) 
            {
                return $e->getMessage();
            }
        }

        return $this->requestContent;
       
    }

    /**
     * Get a random User Agent from useragents.json file
     * 
     * @throws APIClientException
     * 
     * @return mixed: Returns a random User Agent
     */
    public static function getRandomUserAgent(): mixed
    {
        try {
            $filePath = __DIR__  . '/data/useragents.json';
            
            Validator::checkUserAgentFileExists($filePath);
            
            $userAgentsJson = (string) file_get_contents($filePath);
            
            $userAgents = (array) json_decode($userAgentsJson, true);

            Validator::checkUserAgentFileEmptyOrNoArray($userAgents);
            
        } 
        catch (\Exception $e) {
            throw new APIClientException('An error occurred: ' . $e->getMessage());
        }
        
        // Return a random user agent from the array
        return $userAgents[array_rand($userAgents)];
    }
}