<?php

namespace App\Commands;

use App\Providers\Scrapers\ComesConnectedScrapper;
use Exception;
use Goutte\Client as Goutte;
use LaravelZero\Framework\Commands\Command;
use Symfony\Component\DomCrawler\Crawler;

class ScrapCommand extends Command
{

    /**
     * Scrapping results array
     * @var array
     */
    private array $results = array();

    /**
     * Scrapping results elements
     * @var array
     */
    private array $elements = array();

    /**
     * Scrapping results array
     * @var object
     */
    private object $client;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'scrap {--u=}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Scraps target webpage, based on url in SCRAP_URL from .env';


    /**
     * @param Crawler $crawler
     * @return void
     */
    private function prepareScrapData(Crawler $crawler): void
    {
        // get packages from content
        $crawler->filter('.package')->each(function ($node) {
            // get basic values
            $optionTitle = $node->filter('.header.dark-bg > h3')->text();
            $optionDescription = $node->filter('.package-name')->text();
            $packagePriceBlock = $node->filter('.package-price');
            $optionPrice = $packagePriceBlock->filter('.price-big')->text();
            $optionPrice = $this->cleanCurrencies('£', $optionPrice);
            // search for discount text, no discount applied if nothing found
            $discount = $node->filter('.package-price')->each(function (Crawler $packagePrice) {
                $discountText = $packagePrice->filter('p');
                if ($discountText->count()) {
                    return $discountText->text();
                }
                return 'No discount';
            });
            // check if any data is present
            if (
                !is_string($optionTitle)
                || !is_string($optionDescription)
                || !is_string($optionPrice)
            ) {
                $this->error('Error occured while parsing node');
                return;
            }
            // push element array to $this->elements
            $this->elements[] = [
                'option_title' => $optionTitle,
                'option_description' => $optionDescription,
                'option_price' => $optionPrice,
                'option_currency' => 'GBP',
                'option_discount' => $discount,
            ];
        });
    }

    /**
     * @param string $url
     * @return bool
     */
    private function validateUrl( string $url): ?bool
    {
        if ($url !== env('SCRAP_URL')) {
            return null;
        }
        return true;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $crawler = null;
        $plugin = null;

        // get url from input
        $url = $this->option('u');

        // check if we have some url
        if (is_null($url) || $url === '') {
            $this->error('No url provided');
            return;
        }

        // check if url is url
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            $this->error('This url is bad...');
            return;
        }

        // check if we support this url
        if (!$this->validateUrl($url)) {
            $this->error('This url is not supported');
            return;
        }

        // initialize scrapping client
        $this->client = new Goutte();

        if ($url === env('SCRAP_URL')) {
            $plugin = new ComesConnectedScrapper();
        }

        try {
            $crawler = $this->client->request('GET', $url);
        } catch (Exception $e) {
            $this->error('Error occured: ' . $e->getMessage());
        }
        if ($crawler instanceof Crawler) {
            $this->prepareScrapData($crawler);
            if (count($this->elements) > 0) {
                ksort($this->elements);
                $this->setResults($this->elements);
                $this->info($this->getJsonResults());
                return;
            }
            $this->error('No data gathered');
            return;
        }
        $this->error('General error');
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
     * @param array $result
     * @return void
     */
    public function setResults(array $result): void
    {
        $this->results = $result;
    }

    /**
     * @return string
     */
    private function getJsonResults(): string
    {
        return json_encode($this->results);
    }

    /**
     * @return array
     */
    public function getResults(): array
    {
        return $this->results;
    }
}
