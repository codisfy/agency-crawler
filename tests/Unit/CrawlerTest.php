<?php

namespace Tests\Unit;

use App\Modules\Crawl\Crawler;
use PHPUnit\Framework\TestCase;

class CrawlerTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $crawler = new Crawler('https://agencyanalytics.com', 5);
        $crawler->crawl();
    }
}
