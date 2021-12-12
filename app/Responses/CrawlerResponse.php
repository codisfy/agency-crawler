<?php
/**
 * @author    Nikhil <codisfy@gmail.com>
 **/

namespace App\Responses;

use App\Modules\Crawl\CrawlResult;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Used to send out an array response
 */
class CrawlerResponse implements Arrayable
{
    public function __construct(public array $result)
    {
    }

    public function toArray(): array
    {
        $data = [];

        /**
         * @var CrawlResult $crawlResult;
         */
        foreach ($this->result as $crawlResult) {
            $data[] = [
                'url' => $crawlResult->getURL(),
                'code' => $crawlResult->getHTTPCode(),
                'word_count' => $crawlResult->getWords(),
                'title' => $crawlResult->getTitle(),
                'links' => $crawlResult->getLinks(),
                'images' => $crawlResult->getImages(),
                'load_time' => $crawlResult->getTotalTime(),
                'error' => $crawlResult->getError()
            ];
        }
        return $data;
    }
}
