<?php


namespace App\Controller;


use App\Helper\StringHelper;
use League\Glide\Filesystem\FileNotFoundException;
use League\Glide\Responses\SymfonyResponseFactory;
use League\Glide\ServerFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ImageController
{
    /** @var string $source */
    private $source;

    /** @var string $cache */
    private $cache;

    public function __construct(
        string $source,
        string $cache
    )
    {
       $this->source = $source;
       $this->cache = $cache;
    }

    public function show(Request $request): Response
    {
        $path = StringHelper::replaceLastMiddleDashToDot(
            $request->attributes->get('path')
        );

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