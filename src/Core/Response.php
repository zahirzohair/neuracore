<?php

namespace Zahirzohair\Neuracore\Core;

class Response
{
    public static function html(string $content, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: text/html; charset=UTF-8');
        echo $content;
    }

    public static function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }
}
