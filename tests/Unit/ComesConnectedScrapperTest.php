<?php

use App\Providers\Scrapers\ComesConnectedScrapper;

test('scrapper plugin setResults/getResults test', function () {
    $testArray = [];
    $this->comesConMock =  new ComesConnectedScrapper();
    $this->comesConMock->setResults($testArray);
    expect($this->comesConMock->getResults())->toBeArray();
    expect($this->comesConMock->getResults())->toBe($testArray);

    $testString = 'aaa%bbb';
    $testExcludeSymbol = '%';
    expect($this->comesConMock->cleanString($testExcludeSymbol,$testString))->toBeString();
    expect($this->comesConMock->cleanString($testExcludeSymbol,$testString))->toBe('aaabbb');
});

test('scrapper plugin getJsonResults test', function () {
    $testArray = [];
    $this->comesConMock =  new ComesConnectedScrapper();
    $this->comesConMock->setResults($testArray);
    expect($this->comesConMock->getJsonResults())->toBeString();
});

test('scrapper plugin pushResult test', function () {
    $testArray = [];
    $this->comesConMock =  new ComesConnectedScrapper();
    $testArrayElement = ['test'];
    $testArrayFinal = [['test']];
    $this->comesConMock->setResults($testArray);
    $this->comesConMock->pushResult($testArrayElement);
    expect($this->comesConMock->getResults())->toBeArray();
    expect($this->comesConMock->getResults())->toBe($testArrayFinal);
});

test('scrapper plugin cleanString test', function () {
    $this->comesConMock =  new ComesConnectedScrapper();
    $testString = 'aaa%bbb';
    $testExcludeSymbol = '%';
    expect($this->comesConMock->cleanString($testExcludeSymbol,$testString))->toBeString();
    expect($this->comesConMock->cleanString($testExcludeSymbol,$testString))->toBe('aaabbb');
});
