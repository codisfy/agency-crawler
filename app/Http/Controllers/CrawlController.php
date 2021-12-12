<?php

namespace App\Http\Controllers;

use App\Modules\Crawl\Crawler;
use App\Responses\CrawlerResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

// use Inertia class

class CrawlController extends Controller
{

    public function crawl(Request $request)
    {
        $url = $request->get('url');
        $crawler       = new Crawler($url, (int)$request->get('pages', 5));
        $crawlerResult = $crawler->crawl();

        return Inertia::render('Crawl/Index', [
            'crawled_data' => (new CrawlerResponse($crawlerResult))->toArray(),
            'url' => $url
        ]);

    }

}
