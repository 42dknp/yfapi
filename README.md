# API Client for Yahoo Finance Integration

This is an unofficial API Client for Yahoo Finance in PHP. 


## Use it in your project

After installing and validating the package (check vendor folder of your project) you can import different Classes of this project. Here are some features demonstrated:

### autoload.php (not recommended)

Most frameworks setup autoload.php automatically so you can use it after installation out of the box. But if you don´t use a framework / autoloader.php you have to setup to make it work:

```php
require 'vendor/autoload.php';
```

### Get Similar Stocks for a given Stock Symbol

```php
use yfAPI\API\SimilarSecurities;

$getSimilarSecurities = new SimilarSecurities();

$symbol = "NVDA";

$similarSecurities = $getSimilarSecurities->getSimilarSecurities($symbol);

print_r($similarSecurities);
```
Output:
```php
Array
(
    [0] => AMD
    [1] => TSLA
    [2] => META
    [3] => AMZN
    [4] => NFLX
)
```


### Get a Yahoo Finance API Crumb

You will need a valid crumb for usage of certain API calls (or at least if you want to reuse your crumb instead of getting a new one if not necessary).

```php
use yfapi\API\Crumb;

$getCrumb = new Crumb();

$crumb = $getCrumb->getCrumb();

print_r($crumb);
```


### Get Historic Data / Chart Data for Stock Symbols

getStocksCharts expect 3 parameters:
1. **Stock Symbol**: e.g. "AMD"
2. **Start Date**: the function expects a timestamp. You can use DateTime() to generate it.
3. **End Date**: a second timestamp greater than Start Date. 

```php
use yfAPI\API\HistoricData;

$getHistoricData = new HistoricData();

$symbol = "AMD";
$startDate = new \DateTime("-30 days");
$endDate = new \DateTime("today");
$period = "1d";

$historicData = $getHistoricData->getHistoricData($symbol, $startDate, $endDate);
```

#### Helper Functions for Historic Data

You can also use different Helper Functions:

```php
// Get Historic Data for last week
$historicData = $getHistoricData->getHistoricDataLastWeek($symbol);
```
```php
// Get Historic Data for last month
$historicData = $getHistoricData->getHistoricDataLastMonth($symbol);
```
```php
// Get Historic Data for last year
$historicData = $getHistoricData->getHistoricDataLastYear($symbol);
```
```php
// Get Historic Data for YTD
$historicData = $getHistoricData->getHistoricDataYTD($symbol);
```

**Output**
The above code will output an **array** that includes objects for:
- timestamp
- open Price
- low Price
- high Price
- close Price
- adjusted Price

```php
....
[275] => stdClass Object
        (
            [timestamp] => 1675780200
            [open] => 84.319999694824
            [low] => 82.519996643066
            [high] => 86.25
            [close] => 85.910003662109
            [adjclose] => 85.910003662109
        )
...
```

### Get Quote Data for Stock Symbols

getStocksCharts expect 3 parameters:
1. **Stock Symbol**: e.g. "AMD"
2. **Start Date**: the function expects a timestamp. You can use DateTime() to generate it.
3. **End Date**: a second timestamp > than Start Date. 

```php
use yfAPI\API\Quote;

$getQuoteData = new Quote();

$symbol = "GS";

$quoteData = $getQuoteData->getQuote($symbol);

print_r($quoteData);
```

Output:
Here is a list of all Elements:

- fullExchangeName
- symbol
- language
- uuid
- quoteType
- typeDisp
- currency
- exchangeTimezoneName
- exchangeTimezoneShortName
- customPriceAlertConfidence
- marketState
- market
- quoteSourceName
- messageBoardId
- exchange
- shortName
- region
- longName
- fiftyTwoWeekLowChangePercent
- regularMarketOpen
- regularMarketTime
- regularMarketChangePercent
- regularMarketDayRange
- fiftyTwoWeekLowChange
- fiftyTwoWeekHighChangePercent
- regularMarketDayHigh
- sharesOutstanding
- fiftyTwoWeekHigh
- regularMarketPreviousClose
- fiftyTwoWeekHighChange
- marketCap
- regularMarketChange
- fiftyTwoWeekRange
- fiftyTwoWeekLow
- regularMarketPrice
- regularMarketVolume
- regularMarketDayLow

```php
stdClass Object
(
    [fullExchangeName] => NYSE
    [symbol] => GS
    [fiftyTwoWeekLowChangePercent] => 0.09310168  
    [regularMarketOpen] => 315.43
    [language] => en-US
    [regularMarketTime] => 1697035904
    [regularMarketChangePercent] => -0.07306302   
    ...
)
```
### Search 

You can search for Keywords and recieve an array of arrays / objects or raw json string from Search API

```php
use yfAPI\API\Search;

$search = new Search();

$searchTerm = "Advanced Micro";

$getSearchResults = $search->searchFor($searchTerm);

print_r($getSearchResults);
```

The output looks like this:
```php
Array
(
    [0] => stdClass Object
        (
            [exchange] => NMS
            [shortname] => Advanced Micro Devices, Inc.
            [quoteType] => EQUITY
            [symbol] => AMD
            [index] => quotes
            [score] => 259705
            [typeDisp] => Equity
            [longname] => Advanced Micro Devices, Inc.
            [exchDisp] => NASDAQ
            [sector] => Technology
            [sectorDisp] => Technology
            [industry] => Semiconductors
            [industryDisp] => Semiconductors
            [dispSecIndFlag] => 1
            [isYahooFinance] => 1
        )
        ...
```

## Output Formats
Here is a list of default Output formats:

#### Historic Data
**Default**: Object | **Optional**: array, raw (json text string)

#### Quote
**Default**: object | **Optional**: array, raw (json text string)

#### Similar Securities
**Default**: array | **Optional**: raw (json text string)

#### Crumb
**Default**: string (you can´t change the $output attribute) | **Optional**: none

#### Search
**Default**: array of objects | **Optional**: array of arrays, raw (json text string)


## Changing the instance attribute $output you can get a different output format



Example:
```php
use yfAPI\API\SimilarSecurities;

$getSimilarSecurities = new SimilarSecurities();
$getSimilarSecurities->output = "raw"; // you can also change this to "array" or "object" (default)

$symbol = "GS";

$similarSecurities = $getSimilarSecurities->getSimilarSecurities($symbol);

print_r($similarSecurities);
```
So its like the example above but here we set the instance attribute $output to "raw"

## Testing
You can use my Makefile to run PHPUnit tests and phpstan + phpcs (CodeSniffer):
```shell
// run phpunit
make test
```
```shell
// run phpstan & phpcs
make validate
```

There is another Folder with basic tests for API calls. Feel free to run them manually but be aware that it will call external APIs (there might be limits). Test FIles for API are located in **tests/API**

### Coming soon
- Detailed Code Documentation
- Search 
- Dividend History
- Ownerships
- Financial Statements Yearly / Quarterly (Income Statement, Balance Sheets & Cash Flow)
- ESG Scores
- more Helper Functions
- more Test Functions (especially Integration & in-deph API)
- Python version

# Legal Disclaimer

Yahoo!, Y!Finance, and Yahoo! finance are registered trademarks of Yahoo, Inc.

This project is for research purposes only! It is not affiliated, endorsed, or vetted by Yahoo, Inc!
yfapi-php is an open-source API Client for PHP that uses Yahoo's publicly available APIs.
