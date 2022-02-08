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
     * Check if given url is handled by app
     * @param string $url
     * @return bool
     */
    private function validateUrl(string $url): ?bool
    {
        if ($url !== env('VIDEX_SCRAP_URL')) {
            return null;
        }
        return true;
    }

    private function sortResults(array $array)
    {
        usort($array, function ($a, $b) {
            return $b['option_price'] <=> $a['option_price'];
        });
        return $array;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
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
        $client = new Goutte();

        // assign scrapping plugin to match url target
        if ($url === env('VIDEX_SCRAP_URL')) {
            $plugin = new ComesConnectedScrapper();
        }
        // try to get and process data
        try {
            $crawler = $client->request('GET', $url);
            if (!($crawler instanceof Crawler)) {
                $this->error('Crawler error occured ');
                return;
            }
            $plugin->processHtmlData($crawler);
            if (count($plugin->getResults()) > 0) {
                // return scrapped json values
                $results = $this->sortResults($plugin->getResults());
                $plugin->setResults($results);
                $this->info($plugin->getJsonResults());
                return;
            }
            // return no results info
            $this->info('No data gathered');
            return;
        } catch (Exception $e) {
            // display error
            $this->error('Error occured: ' . $e->getMessage());
            return;
        }
    }
}
