<?php


namespace App\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ViteAssetExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('vite_asset', [$this, 'asset'], ['is_safe' => ['html']])
        ];
    }

    public function asset(string $entry, array $deps): string
    {
        $html = <<<HTML
            <script type="module" src="http://localhost:3000/assets/@vite/client"></script>
        HTML;

        if (in_array('vite', $deps)) {
            $html .= <<<HTML
                <div id="viteDev"></div>
            HTML;
        }

        $html .= <<<HTML
            <script type="module" src="http://localhost:3000/assets/{$entry}"></script>
        HTML;

        return $html;
    }
}