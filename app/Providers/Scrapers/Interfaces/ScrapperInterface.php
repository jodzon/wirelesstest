<?php

namespace App\Providers\Scrapers\Interfaces;

use Symfony\Component\DomCrawler\Crawler;

interface ScrapperInterface
{

    /**
     * @param Crawler $crawler
     * @return mixed
     */
    public function processHtmlData(Crawler $crawler);

    /**
     * @param array $result
     * @return void
     */
    public function pushResult(array $result): void;

    /**
     * @param array $results
     * @return void
     */
    public function setResults(array $results): void;

    /**
     * @return array
     */
    public function getResults(): array;

    /**
     * @return mixed
     */
    public function getJsonResults();

}

