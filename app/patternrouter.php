<?php
namespace App;

class PatternRouter
{
    private function stripParameters($uri)
    {
        if (str_contains($uri, '?')) {
            $uri = substr($uri, 0, strpos($uri, '?'));
        }
        return $uri;
    }

    public function route($uri)
    {
        $api = false;

        // Check if the request is for the API
        if (str_starts_with($uri, "api/")) {
            $uri = substr($uri, 4); // Remove the "api/" prefix
            $api = true;
        }

        $uri = $this->stripParameters($uri);

        $explodedUri = explode('/', $uri);

        if (!isset($explodedUri[0]) || empty($explodedUri[0])) {
            $explodedUri[0] = 'home';
        }

        // Determine the controller namespace based on whether it's an API request
        $namespace = $api ? "App\\Api\\Controllers\\" : "App\\Controllers\\";
        $controllerName = $namespace . ucfirst($explodedUri[0]) . "Controller";

        if (isset($explodedUri[1]) && is_numeric($explodedUri[1])) {
            // Dynamic route: e.g., products/{id}
            $methodName = 'show'; // Define a convention for dynamic routes
            $parameter = $explodedUri[1];
        } else {
            // Default method (index)
            $methodName = $explodedUri[1] ?? 'index';
            $parameter = null;
        }

        // Controller/method matching the URL not found
        if (!class_exists($controllerName) || !method_exists($controllerName, $methodName)) {
            http_response_code(404);
            echo json_encode(["error" => "404 Not Found"]); // JSON response for API
            return;
        }

        try {
            $controllerObj = new $controllerName();

            // Call method with or without parameter
            $response = $parameter ? $controllerObj->$methodName($parameter) : $controllerObj->$methodName();

            // If it's an API request, return JSON response

        } catch (\Error $e) {
            // Handle internal errors
            http_response_code(500);
            $error = ["error" => "500 Internal Server Error"];
            if ($api) {
                header('Content-Type: application/json');
                echo json_encode($error);
            } else {
                echo "500 Internal Server Error";
            }
        }
    }

}