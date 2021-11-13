<?php
// Application middleware
use \Slim\Middleware\JwtAuthentication;
// e.g: $app->add(new \Slim\Csrf\Guard);
$app->add(new JwtAuthentication([
    "secure" => false,
    "path" => "/api_v1",
    "attribute" => "user_token",
    "secret" => "supersecretkeyherudiganteng",
    "algorithm" => ["HS256"],
    "error" => function ($request, $response, $arguments) {
        $data["status"] = "error";
        $data["message"] = "Not Authorizing";
        return $response
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }
]));