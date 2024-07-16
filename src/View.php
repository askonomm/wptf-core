<?php

namespace Wptf\Core;

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

readonly class View
{
    public function __construct(private string $dir)
    {
    }

    /**
     * @param string $view
     * @param array $data
     * @return string
     * @throws \Exception
     */
    public function make(string $view, array $data = []): string
    {
        if (!file_exists("{$this->dir}/{$view}.twig")) {
            throw new \Exception("View {$view} not found");
        }

        try {
            $loader = new \Twig\Loader\FilesystemLoader($this->dir);
            $twig = new \Twig\Environment($loader, [
                'auto_reload' => true,
                'debug' => true,
                'cache' => false,
            ]);

            // Register template functions
            $config = require __DIR__ . '/Config/twig.php';

            if (file_exists(get_template_directory() . '/src/Config/twig.php')) {
                $user_config = require get_template_directory() . '/src/Config/twig.php';
                $config = [...$config, ...$user_config];
            }

            foreach ($config as $name => $callable) {
                $twig->addFunction(new \Twig\TwigFunction($name, $callable));
            }

            return $twig->render("{$view}.twig", $data);
        } catch (SyntaxError|RuntimeError|LoaderError $e) {
            return $e->getMessage();
        }
    }
}