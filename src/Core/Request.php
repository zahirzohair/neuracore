<?php

namespace Zahirzohair\Neuracore\Core;

class Request
{
    private array $get;
    private array $post;
    private array $server;

    public function __construct(array $get = [], array $post = [], array $server = [])
    {
        $this->get = $get ?: $_GET;
        $this->post = $post ?: $_POST;
        $this->server = $server ?: $_SERVER;
    }

    // Get a value from POST or GET
    public function input(string $key, $default = null)
    {
        return $this->post[$key] ?? $this->get[$key] ?? $default;
    }

    // Get all input data
    public function all(): array
    {
        return array_merge($this->get, $this->post);
    }

    // Get HTTP method
    public function method(): string
    {
        return $this->server['REQUEST_METHOD'] ?? 'GET';
    }

    // Get current URI
    public function uri(): string
    {
        $uri = parse_url($this->server['REQUEST_URI'] ?? '/', PHP_URL_PATH);

        $basePath = '/neuracore/public';
        $uri = str_replace($basePath, '', $uri);

        return $uri ?: '/';
    }
}
