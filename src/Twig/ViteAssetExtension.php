<?php


namespace App\Twig;


use Error;
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

    /**
     * @param string $entry
     * @param array<int, string> $deps
     * @return string
     */
    public function asset(string $entry, array $deps): string
    {
        if ($this->env !== 'PROD') {
            return $this->assetDev($entry, $deps);
        }
        return $this->assetProd($entry);
    }

    public function assetProd(string $entry): string
    {
        $rawFile = file_get_contents($this->manifest);
        if ($rawFile === false) {
            throw new Error("Vite manifest ($this->manifest) is not found.");
        }
        $data    = json_decode($rawFile, true);
        $file    = $data[$entry]['file'];
        $css     = $data[$entry]['css'];
        $imports = $data[$entry]['imports'];

        $html = <<<HTML
            <script type="module" src="/build/{$file}" defer></script>
        HTML;

        foreach ($css as $cssFile) {
            $html .= <<<HTML
                <link rel="stylesheet" media="screen" href="/build/{$cssFile}">
            HTML;
        }

        foreach ($imports as $importFile) {
            $html .= <<<HTML
                <link rel="modulepreload" href="/build/{$data[$importFile]['file']}">
            HTML;
        }

        return $html;
    }

    /**
     * @param string $entry
     * @param array<int, string> $deps
     * @return string
     */
    public function assetDev(string $entry, array $deps): string
    {
        $html = <<<HTML
            <script type="module" src="https://localhost:3000/assets/@vite/client"></script>
        HTML;

        if (in_array('react', $deps)) {
            // Add react HRM script if needed, or any other script's dependencies
            $html .= <<<HTML
                
            HTML;
        }

        $html .= <<<HTML
            <script type="module" src="https://localhost:3000/assets/{$entry}" defer></script>
        HTML;

        return $html;
    }
}