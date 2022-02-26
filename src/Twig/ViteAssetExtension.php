<?php


namespace App\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ViteAssetExtension extends AbstractExtension
{
    private string $env;
    private string $manifest;

    public function __construct(
        string $env,
        string $manifest,
    ) {
        $this->env      = $env;
        $this->manifest = $manifest;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('vite_asset', [$this, 'asset'], ['is_safe' => ['html']])
        ];
    }

    public function asset(string $entry, array $deps): string
    {
        if ($this->env !== 'PROD') {
            return $this->assetDev($entry, $deps);
        }
        return $this->assetProd($entry);
    }

    public function assetProd(string $entry): string
    {
        $html = '';

        $rawFile = file_get_contents($this->manifest);
        $data    = json_decode($rawFile, true);
        $file    = $data[$entry]['file'];
        $css     = $data[$entry]['css'];
        $imports = $data[$entry]['imports'];

        $html = <<<HTML
            <script type="module" src="/assets/{$file}" defer></script>
        HTML;

        foreach ($css as $cssFile) {
            $html .= <<<HTML
                <link rel="stylesheet" media="screen" href="/assets/{$cssFile}">
            HTML;
        }

        foreach ($imports as $importFile) {
            $html .= <<<HTML
                <link rel="modulepreload" href="/assets/{$importFile}">
            HTML;
        }

        return $html;
    }

    public function assetDev(string $entry, array $deps): string
    {
        $html = <<<HTML
            <script type="module" src="http://localhost:3000/assets/@vite/client"></script>
        HTML;

        if (in_array('react', $deps)) {
            // Add react HRM script if needed, or any other script's dependencies
            $html .= <<<HTML
                
            HTML;
        }

        $html .= <<<HTML
            <script type="module" src="http://localhost:3000/assets/{$entry}" defer></script>
        HTML;

        return $html;
    }
}