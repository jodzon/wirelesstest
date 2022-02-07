<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use Goutte\Client as Goutte;
use Symfony\Component\DomCrawler\Crawler;
use Exception;

use function PHPUnit\Framework\isInstanceOf;

class ScrapCommand extends Command
{

    /**
     * Scrapping results array
     * @var array
     */
    private $results = array();

    /**
     * Scrapping results array
     * @var array
     */
    private $elements = array();

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // new Goutte Client
        $crawler = null;
        $url = $this->option('u');
        if (is_null($url) || $url === '') {
            $this->error('No url provided');
            return;
        }
        $this->client = new Goutte();
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
    public function pushResult(array $result): void
    {
        $this->results[] = $result;
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
