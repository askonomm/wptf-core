<?php

namespace Asko\Wptf;

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class Response
{
    private string $response = '';
    private int $status = 200;
    private array $headers = [];

    /**
     * @param string $view
     * @param array $data
     * @param int $status
     * @return static
     * @throws \Exception
     */
    public static function view(string $view, array $data = [], int $status = 200): static
    {
        if (!file_exists(get_template_directory() . '/src/Views/' . $view . '.twig')) {
            throw new \Exception("View {$view} not found");
        }

        $self = new self();

        try {
            $loader = new \Twig\Loader\FilesystemLoader(get_template_directory() . '/src/Views');
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

            foreach($config as $name => $callable) {
                $twig->addFunction(new \Twig\TwigFunction($name, $callable));
            }

            $self->status = $status;
            $self->response = $twig->render("{$view}.twig", $data);
        } catch(SyntaxError | RuntimeError | LoaderError $e) {
            $self->status = 500;
            $self->response = $e->getMessage();
        }

        return $self;
    }

    /**
     * @param array $data
     * @param int $status
     * @return static
     */
    public static function json(array $data, int $status = 200): static
    {
        $self = new self();
        $self->status = $status;
        $self->response = json_encode($data);
        $self->headers = [
            'Content-Type' => 'application/json',
        ];

        return $self;
    }

    /**
     * @return string
     */
    public function make(): string
    {
        http_response_code($this->status);

        foreach($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }

        return $this->response;
    }
}