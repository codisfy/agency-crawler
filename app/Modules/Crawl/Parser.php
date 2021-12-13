<?php
/**
 * @author    Nikhil <codisfy@gmail.com>
 **/

namespace App\Modules\Crawl;

use DOMDocument;
use DOMXPath;
use Illuminate\Support\Str;

class Parser
{

    protected DOMDocument|null $dom = null;

    public function __construct(protected string $html, protected string $baseURL)
    {

    }

    protected function getDomDocument($cached = true): DOMDocument
    {
        if (!$this->dom || !$cached) {
            @$this->dom = new DOMDocument();
            @$this->dom->loadHTML($this->html);
            $this->dom->preserveWhiteSpace = false;
        }

        return $this->dom;
    }

    public function getImages()
    {
        $dom = $this->getDomDocument();
        // Create DOMXpath and get images
        $xpath = new DOMXpath($dom);
        $imgs  = $xpath->query("//img");

        // Return array
        $ret = [];

        // Loop results and return
        for ($i = 0; $i < $imgs->length; $i++) {
            $img = $imgs->item($i);
            $src = $img?->getAttribute("src");

            // Make sure it's a complete URL
            $src = $this->filterUrl($src);
            if ($src === false) {
                $src = $this->filterUrl($img?->getAttribute("data-src"));
                if ($src === false) {
                    continue;
                }
            }

            $ret[$src] = 1;
        }

        return array_keys($ret);
    }

    public function getTitle()
    {
        $dom = $this->getDomDocument();
        // Create DOMXpath and get images
        $xpath = new DOMXpath($dom);
        $title = $xpath->query("//title");
        return $title->item(0)->textContent;
    }


    /**
     * Get page word count
     *
     * @return int
     */
    public function getWordCount()
    {

        $dom = $this->getDomDocument(false);

        while (($r = $dom->getElementsByTagName("script")) && $r->length) {
            $r->item(0)->parentNode->removeChild($r->item(0));
        }

        // Strip styles
        while (($r = $dom->getElementsByTagName("style")) && $r->length) {
            $r->item(0)->parentNode->removeChild($r->item(0));
        }

        return str_word_count($dom->textContent);
    }


    /**
     * Get all links
     *
     * @return array
     */
    public function getLinks(): array
    {

        $dom = $this->getDomDocument();

        // Return array
        $ret = [];

        // Get links
        $links = $dom->getElementsByTagName('a');

        // Extract links data
        foreach ($links as $tag) {

            // Get the hyperlink reference
            $url = trim($tag->getAttribute('href'));

            // Make sure it's a complete URL
            $url = $this->filterUrl($url);
            if ($url === false) {
                continue;
            }

            // Skip mailto links
            if (str_contains($url, "mailto:")) {
                continue;
            }

            $ret[$url] = [
                'title'    => $tag->textContent, ///$tag->childNodes->item(0)->nodeValue
                'internal' => $this->isInternal($url),
            ];
        }

        return $ret;
    }


    private function filterUrl($url)
    {
        if (str_starts_with($url, "#")) {
            return false;
        }

        // assuming we are dealing with https only
        if (str_starts_with($url, "//")) {
            $url = "https:" . $url;
        }

        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return $url;
        }

        if (str_starts_with($url, "/")) {

            $fullUrl = $this->getWebURL() . $url;

            if (filter_var($fullUrl, FILTER_VALIDATE_URL)) {
                return $fullUrl;
            }
        }

        return false;
    }

    private function getWebURL()
    {
        return 'https://' . $this->baseURL;
    }

    private function isInternal(mixed $url)
    {
        return Str::startsWith($url, $this->getWebURL());
    }
}
