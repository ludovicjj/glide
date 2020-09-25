<?php


namespace App\Controller;


use League\Glide\Filesystem\FileNotFoundException;
use League\Glide\Responses\SymfonyResponseFactory;
use League\Glide\ServerFactory;
use League\Glide\Signatures\SignatureException;
use League\Glide\Signatures\SignatureFactory;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AssetController
{
    /** @var string $sourcePath */
    private $sourcePath;

    /** @var string $cachePath */
    private $cachePath;

    /** @var string $secret */
    private $secret;

    public function __construct(
        ParameterBagInterface $parameterBag
    )
    {
       $this->sourcePath = $parameterBag->get('kernel.project_dir').'/public/images';
       $this->cachePath = $parameterBag->get('kernel.cache_dir');
       $this->secret = $parameterBag->get('glide.key');
    }

    public function show(Request $request, int $width, int $height, string $path): Response
    {
        $server = ServerFactory::create([
            'response' => new SymfonyResponseFactory(),
            'cache' => $this->cachePath,
            'source' => $this->sourcePath,
            'cache_path_prefix' => 'images',
        ]);

        [$url] = explode('?', $request->getRequestUri());


        // validate signature
        try {
            SignatureFactory::create($this->secret)->validateRequest($url, ['s' => $request->query->get('s')]);
        } catch (SignatureException $signatureException) {
            throw new HttpException(403, 'Signature invalid');
        }

        try {
            $response = $server->getImageResponse($path, ['w' => $width, 'h' => $height, 'fit' => 'crop']);
        } catch (FileNotFoundException $exception) {
            throw new NotFoundHttpException($exception->getMessage());
        }

        return $response;
    }
}