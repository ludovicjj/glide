<?php


namespace App\Twig;


use App\Helper\AssetPathGenerator;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AssetPathExtension extends AbstractExtension
{
    private $assetGenerator;

    public function __construct(AssetPathGenerator $assetGenerator)
    {
        $this->assetGenerator = $assetGenerator;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('asset_path', [$this, 'assetPath'], ['is_safe' => ['html']])
        ];
    }

    public function assetPath(int $width, int $height, string $path): string
    {
        return $this->assetGenerator->generator($width, $height, $path);
    }

}