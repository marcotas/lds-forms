<?php

namespace App\Services;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\Speech;

class Site
{
    /**
     * @var \GuzzleHttp\Client
     */
    private $client;
    /**
     * @var string
     */
    private $baseUrl;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function setUrl(string $url): self
    {
        $this->baseUrl = $url;

        return $this;
    }

    public function url()
    {
        list($url) = explode('?', $this->baseUrl);

        return $url;
    }

    public function baseUrl()
    {
        extract(parse_url($this->baseUrl));

        return $scheme . '://' . $host;
    }

    public function query()
    {
        list($_, $query) = explode('?', $this->baseUrl);

        return $query;
    }

    public function getSpeeches()
    {
        $html     = file_get_contents($this->baseUrl);
        $crawler  = new Crawler($html);
        $nodes    = $crawler->filter('.conference-page .lumen-tile--list');
        $speeches = collect();

        $nodes->each(function (Crawler $node) use (&$speeches) {
            $speeches->push($this->parseSpeechFromNode($node));
        });

        return $speeches;
    }

    private function clearText($text)
    {
        return trim(preg_replace('/(\t|\n)+/', '', $text));
    }

    public function parseSpeechFromNode(Crawler $node): Speech
    {
        $title     = $this->clearText($node->filter('.lumen-tile__title')->text());
        $link      = $this->baseUrl() . $node->filter('.lumen-tile__link')->attr('href');
        $image_url = collect($node->filter('img.lumen-image__image')->extract(['src']))->last();
        $author    = $node->filter('.lumen-tile__content')->count()
            ? $node->filter('.lumen-tile__content')->text()
            : null;

        return new Speech(compact('title', 'link', 'image_url', 'author'));
    }
}
