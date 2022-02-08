<?php

use App\Providers\Scrapers\ComesConnectedScrapper;

test('comesconnectedscrapper', function () {
    $testArray = [];
    $this->comesConMock =  new ComesConnectedScrapper();
    $this->comesConMock->setResults($testArray);
    expect($this->comesConMock->getResults())->toBeArray();
    expect($this->comesConMock->getJsonResults())->toBeString();
});
