<?php

use App\Providers\Scrapers\ComesConnectedScrapper;

test('comesconnectedscrapper', function () {
    $testArray = [];
    $this->comesConMock =  new ComesConnectedScrapper();
    $this->comesConMock->setResults($testArray);
    expect($this->comesConMock->getResults())->toBeArray();
    expect($this->comesConMock->getResults())->toBe($testArray);
    expect($this->comesConMock->getJsonResults())->toBeString();

    $testArrayElement = ['test'];
    $testArrayFinal = [['test']];
    $this->comesConMock->setResults($testArray);
    $this->comesConMock->pushResult($testArrayElement);
    expect($this->comesConMock->getResults())->toBeArray();
    expect($this->comesConMock->getResults())->toBe($testArrayFinal);

});
