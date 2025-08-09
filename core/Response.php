<?php

namespace Core;

class Response
{
    public function send(int $httpCode, string $message, array $data = [], array $headers = []): void
    {
        $responseBody = [
            "success" => $httpCode < 400,
            "message" => $message,
        ];

        if (!empty($data)) {
            $responseBody = array_merge($responseBody, $data);
        }

        http_response_code($httpCode);
        header('Content-Type: application/json');

        foreach ($headers as $key => $value) {
            header("$key: $value");
        }

        die(json_encode($responseBody));
    }
}
