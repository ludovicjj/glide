<?php


namespace App\Helper;

use League\Glide\Urls\UrlBuilderFactory;

class AssetPathGenerator
{
    /** @var string $secret */
    private $secret;

    public function __construct(
        string $secret
    )
    {
        $this->secret = $secret;
    }

    public function generator(int $width, int $height, string $path): string
    {
        $baseUrl = '/resize';
        $url = "/{$width}/{$height}/{$path}";
        $urlBuilder = UrlBuilderFactory::create($baseUrl, $this->secret);

        return $urlBuilder->getUrl($url);
    }
}