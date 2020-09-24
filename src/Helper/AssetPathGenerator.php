<?php


namespace App\Helper;


use League\Glide\Signatures\SignatureFactory;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AssetPathGenerator
{
    /** @var UrlGeneratorInterface $urlGenerator */
    private $urlGenerator;

    /** @var string $secret */
    private $secret;

    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        string $secret
    )
    {
        $this->urlGenerator = $urlGenerator;
        $this->secret = $secret;
    }

    public function generator(string $path, array $parameters = []): string
    {
        // generate signature
        $signature = SignatureFactory::create($this->secret)->generateSignature($path, $parameters);
        $parameters['s'] = $signature;
        $parameters['path'] = $path;

        return $this->urlGenerator->generate(
            'asset',
            $parameters,
            UrlGeneratorInterface::ABSOLUTE_PATH
        );
    }
}