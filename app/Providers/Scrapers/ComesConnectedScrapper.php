<?php

namespace App\Providers\Scrapers;

use App\Providers\Scrapers\Interfaces\ScrapperInterface;
use Symfony\Component\DomCrawler\Crawler;

class ComesConnectedScrapper implements ScrapperInterface
{

    /**
     * @var array $results
     */
    private array $results;

    /**
     * @param array $result
     * @return void
     */
    public function setResults(array $result): void
    {
        $this->results = $result;
    }

    /**
     * Returns json encoded $results
     * @return string
     */
    private function getJsonResults(): string
    {
        return json_encode($this->results);
    }

    /**
     * Returns $results
     * @return array
     */
    public function getResults(): array
    {
        return $this->results;
    }

    /**
     * Removes given currency symbol from given text
     * @param string $symbol
     * @param string $text
     * @return string
     */
    private function cleanCurrencies(string $symbol, string $text): string
    {
        return str_replace($symbol, '', $text);
    }

    /**
     *
     * @param Crawler $htmlData
     * @return void
     */
    public function processHtmlData(Crawler $htmlData)
    {

    }

}

