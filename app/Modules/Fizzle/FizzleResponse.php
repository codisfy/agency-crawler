<?php
/**
 * @author    Nikhil <codisfy@gmail.com>
 **/

namespace App\Modules\Fizzle;

class FizzleResponse
{
    public function __construct(
        protected string $content,
        protected array $info,
    ) {
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param  string  $content
     * @return FizzleResponse
     */
    public function setContent(string $content): FizzleResponse
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getInfo(): ?array
    {
        return $this->info;
    }

    /**
     * @param  array  $info
     * @return FizzleResponse
     */
    public function setInfo(array $info): FizzleResponse
    {
        $this->info = $info;

        return $this;
    }

    public function getContentType()
    {
        return $this->info['content_type'] ?? null;
    }

    public function getHTTPCode()
    {
        return $this->info['http_code'] ?? null;
    }

    public function getTotalTime()
    {
        return $this->info['total_time'] ?? null;
    }


}
