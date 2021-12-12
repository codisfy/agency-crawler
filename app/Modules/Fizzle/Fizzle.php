<?php

namespace App\Modules\Fizzle;

use App\Exceptions\CurlException;

/**
 * A guzzle copy cat
 */
class Fizzle
{


    /**
     * Makes an HTTP request returns an array with content type and response
     *
     * @param $url
     * @param  array  $params
     * @return FizzleResponse
     * @throws CurlException
     */
    public function request($url, array $params = [])
    {

        $ch      = \curl_init();
        $options = [
            CURLOPT_URL            => $url,
            CURLOPT_USERAGENT      => 'AgencyCrawler',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
        ];

        if (! empty($params)) {
            $options[CURLOPT_POST]       = true;
            $options[CURLOPT_POSTFIELDS] = is_array($params) ?
                http_build_query($params) : $params;
        }

        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);

        if (curl_error($ch)) {
            curl_close($ch);
            throw new CurlException($url . " || " . curl_error($ch));
        }
        $info = curl_getinfo($ch);

        return new FizzleResponse($response, $info);
    }
}
