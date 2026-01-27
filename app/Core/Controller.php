<?php

namespace App\Core;

class Controller
{
    protected function view($view, $data = [])
    {
        // Extract data to make it available as variables in the view
        extract($data);

        // Define path to view file
        $viewPath = __DIR__ . '/../Views/' . $view . '.php';

        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("View '$view' not found at $viewPath");
        }
    }

    protected function redirect($url)
    {
        header("Location: $url");
        exit;
    }

    protected function jsonResponse($data, $statusCode = 200)
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }
}
