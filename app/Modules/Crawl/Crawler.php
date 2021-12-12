<?php

namespace App\Modules\Crawl;

use App\Modules\Fizzle\Fizzle;
use App\Modules\Fizzle\FizzleResponse;

class Crawler
{
    /**
     * Hold per page information
     *
     * @var array
     */
    public array $crawlerOutput = [];


    /**
     * URLs to crawl
     *
     * @var array
     */
    private array $queue = [];

    /**
     * URLs already processed
     *
     * @var array
     */
    private array $processed = [];


    /**
     * Root URL
     *
     * @var string
     */
    private string $startURL;

    /**
     * Max number of links to process
     *
     * @var int
     */
    private int $crawlLimit;


    public function __construct(string $url, int $crawlLimit)
    {
        $this->startURL   = $url;
        $this->crawlLimit = $crawlLimit;
        $this->addToQueue($url);
    }

    /**
     * Check if the response is a crawlable response
     *
     * @param  FizzleResponse  $response
     * @return bool
     */
    protected function isCrawlable(FizzleResponse $response): bool
    {
        $contentType = $response->getContentType();
        if (str_contains($contentType, "text/")) {
            return true;
        }

        return false;
    }

    protected function getBaseURL()
    {
        $url_info = parse_url($this->startURL);

        return $url_info['host'];//hostname
    }

    /**
     * Crawls the page and returns an array of CrawlResult
     *
     * @return array
     */
    public function crawl(): array
    {
        $fizzle = new Fizzle();

        while ($this->canProcessQueue()) {
            $url = $this->cleanUp(array_shift($this->queue));
            $this->addToProcessed($url);
            try {
                $response = $fizzle->request($url);
                if ($this->isCrawlable($response)) {
                    $parser      = new Parser($response->getContent(), $this->getBaseURL());
                    $images      = $parser->getImages();
                    $links       = $parser->getLinks();
                    $wordCount   = $parser->getWordCount();
                    $title       = $parser->getTitle();
                    $crawlResult = new CrawlResult($url, $response);
                    $crawlResult->setWords($wordCount)
                                ->setImages($images)
                                ->setLinks($links)
                                ->setTitle($title);

                    $this->addValidOutput($crawlResult);
                }
                $this->updateQueue($links);
            } catch (\Exception $e) {
                $this->addErrorOutput($url, $e->getMessage());
            }
        }

        return $this->crawlerOutput;
    }

    /**
     * Add URL to queue if max limit has not been reached
     *
     * @param  string  $url
     * @return void
     */
    private function addToQueue(string $url): void
    {
        if ($this->isUnderCrawlLimit()) {
            $this->queue[] = $url;
        }
    }

    /**
     * Add page to crawler output with an error message
     *
     * @param  mixed  $url
     * @param  string  $message
     * @return void
     */
    private function addErrorOutput(mixed $url, string $message): void
    {
        $this->crawlerOutput[] = new CrawlResult($url, null, $message);
    }

    /**
     * Append to the crawlerOutput
     *
     * @param $result
     * @return void
     */
    private function addValidOutput($result): void
    {
        $this->crawlerOutput[] = $result;
    }

    /**
     * Update the current processing queue if there is capacity
     *
     * @param  array  $links
     * @return void
     */
    private function updateQueue(array $links): void
    {
        foreach ($links as $url => $info) {
            $url = $this->cleanUp($url);
            if ($info['internal'] && empty($this->processed[$url])) {
                $this->addToQueue($url);
            }
        }
    }

    /**
     * Process queue if there are unprocessed links and capacity has not been exhausted
     *
     * @return bool
     */
    private function canProcessQueue(): bool
    {
        return $this->isUnderCrawlLimit() && count($this->queue);
    }

    /**
     * Has crawling limit been reached?
     *
     * @return bool
     */
    private function isUnderCrawlLimit(): bool
    {
        return $this->crawlLimit > count($this->processed);
    }

    /**
     * Add URL to the processed array
     *
     * @param  string  $url
     * @return void
     */
    private function addToProcessed(string $url)
    {
        $this->processed[$url] = 1;
    }

    /**
     * Remove trailing / from the URL
     *
     * @param $url
     * @return mixed|string
     */
    private function cleanUp($url)
    {
        if (substr($url, -1) === '/') {
            $url = substr($url, 0, -1);
        }

        return $url;
    }
}
