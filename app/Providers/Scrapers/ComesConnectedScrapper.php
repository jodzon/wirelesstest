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
     * @param array $results
     * @return void
     */
    public function setResults(array $results): void
    {
        $this->results = $results;
    }

    /**
     * @param array $result
     * @return void
     */
    public function pushResult(array $result): void
    {
        $this->results[] = $result;
    }

    /**
     * Returns json encoded $results
     * @return string
     */
    public function getJsonResults(): string
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
    private function cleanString(string $symbol, string $text): string
    {
        return str_replace($symbol, '', $text);
    }

    /**
     * Process html data from crawler instance
     * @param Crawler $crawler
     * @return void
     */
    public function processHtmlData(Crawler $crawler): void
    {
        // get packages from content
        $crawler->filter('.package')->each(function ($node) {
            // get basic values
            $optionTitle = $node->filter('.header.dark-bg > h3')->text();
            $optionDescription = $node->filter('.package-name')->text();
            $packagePriceBlock = $node->filter('.package-price');
            $optionPrice = $packagePriceBlock->filter('.price-big')->text();
            $optionPrice = $this->cleanString('£', $optionPrice);
            // search for discount text, no discount applied if nothing found
            $discount = $node->filter('.package-price')->each(function (Crawler $packagePrice) {
                $discountText = $packagePrice->filter('p');
                if ($discountText->count()) {
                    return $discountText->text();
                }
                return 'No discount';
            });
            // push element array to $this->elements
            $result = [
                'option_title' => $optionTitle,
                'option_description' => $optionDescription,
                'option_price' => $optionPrice,
                'option_currency' => 'GBP',
                'option_discount' => $discount,
            ];
            $this->pushResult($result);
        });
    }

}

