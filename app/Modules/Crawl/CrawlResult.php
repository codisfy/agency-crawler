<?php
/**
 * @author    Nikhil <codisfy@gmail.com>
 **/

namespace App\Modules\Crawl;

use App\Modules\Fizzle\FizzleResponse;

class CrawlResult
{
    protected array  $links;
    protected array  $images;
    protected int    $words;
    protected string $title;

    public function __construct(
        protected string              $url,
        protected FizzleResponse|null $response,
        protected string|null         $error = null,
    ) {
    }

    /**
     * @return string
     */
    public function getURL()
    {
        return $this->url;
    }

    /**
     * Check if this result had an error
     *
     * @return bool
     */
    public function hasError()
    {
        return ! empty($this->error);
    }

    public function getError()
    {
        return $this->error;
    }

    /**
     * @return array
     */
    public function getLinks(): ?array
    {
        return $this->links;
    }

    /**
     * @param  array  $links
     * @return CrawlResult
     */
    public function setLinks(array $links): CrawlResult
    {
        $this->links = $links;

        return $this;
    }

    /**
     * @return array
     */
    public function getImages(): ?array
    {
        return $this->images;
    }

    /**
     * @param  array  $images
     * @return CrawlResult
     */
    public function setImages(array $images): CrawlResult
    {
        $this->images = $images;

        return $this;
    }

    /**
     * @return int
     */
    public function getWords(): ?int
    {
        return $this->words;
    }

    /**
     * @param  int  $words
     * @return CrawlResult
     */
    public function setWords(int $words): CrawlResult
    {
        $this->words = $words;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param  string  $title
     * @return CrawlResult
     */
    public function setTitle(string $title): CrawlResult
    {
        $this->title = $title;

        return $this;
    }


    public function getHTTPCode()
    {
        return $this->response->getHTTPCode();
    }

    public function getTotalTime()
    {
        return $this->response->getTotalTime();
    }


}
