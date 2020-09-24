<?php


namespace App\Controller;


use League\Glide\Filesystem\FileNotFoundException;
use League\Glide\Responses\SymfonyResponseFactory;
use League\Glide\ServerFactory;
use League\Glide\Signatures\SignatureException;
use League\Glide\Signatures\SignatureFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AssetController
{
    /** @var string $source */
    private $source;

    /** @var string $cache */
    private $cache;

    /** @var string $secret */
    private $secret;

    public function __construct(
        string $source,
        string $cache,
        string $secret
    )
    {
       $this->source = $source;
       $this->cache = $cache;
       $this->secret = $secret;
    }

    public function show(Request $request): Response
    {
        $path = $request->attributes->get('path');

        // validate signature
        try {
            SignatureFactory::create($this->secret)->validateRequest($path, $request->query->all());
        } catch (SignatureException $signatureException) {
            throw new NotFoundHttpException($signatureException->getMessage());
        }

        $server = ServerFactory::create([
            'response' => new SymfonyResponseFactory($request),
            'cache' => $this->cache,
            'source' => $this->source,
            'cache_path_prefix' => '.images',
            'base_url' => 'img'
        ]);

        try {
            $response = $server->getImageResponse($path, $request->query->all());
        } catch (FileNotFoundException $exception) {
            throw new NotFoundHttpException($exception->getMessage());
        }

        return $response;
    }
}