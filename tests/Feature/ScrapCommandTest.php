<?php

use App\Commands\ScrapCommand;
use Goutte\Client as Goutte;

$properURL = 'https://videx.comesconnected.com/';
$notProperURL = 'https://google.com';
$badURL = 'badurl';
$properJsonResult = '[{"option_title":"Option 3600 Mins","option_description":"Up to 3600 minutes talk time per year including 480 SMS(5p \/ minute and 4p \/ SMS thereafter)","option_price":"174.00","option_currency":"GBP","option_discount":["Save \u00a318 on the monthly price"]},{"option_title":"Option 2000 Mins","option_description":"Up to 2000 minutes talk time per year including 420 SMS(5p \/ minute and 4p \/ SMS thereafter)","option_price":"108.00","option_currency":"GBP","option_discount":["Save \u00a312 on the monthly price"]},{"option_title":"Option 480 Mins","option_description":"Up to 480 minutes talk time per yearincluding 240 SMS(5p \/ minute and 4p \/ SMS thereafter)","option_price":"66.00","option_currency":"GBP","option_discount":["Save \u00a35 on the monthly price"]},{"option_title":"Option 300 Mins","option_description":"300 minutes talk time per monthincluding 40 SMS(5p \/ minute and 4p \/ SMS thereafter)","option_price":"16.00","option_currency":"GBP","option_discount":["No discount"]},{"option_title":"Option 160 Mins","option_description":"Up to 160 minutes talk time per monthincluding 35 SMS(5p \/ minute and 4p \/ SMS thereafter)","option_price":"10.00","option_currency":"GBP","option_discount":["No discount"]},{"option_title":"Option 40 Mins","option_description":"Up to 40 minutes talk time per monthincluding 20 SMS(5p \/ minute and 4p \/ SMS thereafter)","option_price":"6.00","option_currency":"GBP","option_discount":["No discount"]}]';

test('crawler class check', function ($url) {
    $this->client = Mockery::mock(Goutte::class);
    $this->client->shouldReceive('request')->with($url)->andReturn(Symfony\Component\DomCrawler\Crawler::class);
})->with(
    [$properURL],
    [$notProperURL],
    [$badURL],
);

test('crawler class check', function ($url) {

});


test('scrap command called from command line with various urls', function () use ($properJsonResult) {
    $this->artisan('scrap')
        ->expectsOutput('No url provided')
        ->assertExitCode(0);

    $this->artisan('scrap  --u')
        ->expectsOutput('No url provided')
        ->assertExitCode(0);

    $this->artisan('scrap  --u asafgadgadfafda')
        ->expectsOutput('This url is bad...')
        ->assertExitCode(0);

    $this->artisan('scrap --u https://google.com/')
        ->expectsOutput('This url is not supported')
        ->assertExitCode(0);

    $this->artisan('scrap --u https://videx.comesconnected.com/')
        ->expectsOutput($properJsonResult)
        ->assertExitCode(0);
});


