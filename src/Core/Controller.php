<?php

namespace Zahirzohair\Neuracore\Core;

class Controller
{
    protected function json($data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function view(string $template, array $data = []): void
    {
        extract($data);
        require __DIR__ . "/../../views/{$template}.php";
    }

    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }
}
