<?php

namespace App\Providers\Scrapers\Interfaces;

use Symfony\Component\DomCrawler\Crawler;

interface ScrapperInterface
{
    public function processHtmlData(Crawler $htmlData);

}

