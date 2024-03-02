<?php

namespace Core;

use DateTime;
use Exception;

class RouteResolver {

    private const DEFAULT_DATE_FORMAT = "Y-m-d H:i:s";
    private array $env;
    private array $server;
    private array $routes;
    private Logger $logger;
    private Request $request;

    public function __construct(
        array $env,
        array $server,
    )
    {
        $this->env = $env;
        $this->server = $server;
        $this->logger = new Logger($this->server);
        $this->request = Request::getInstance();
        $pid = pcntl_fork();
    
        if ($pid == -1) {
            // Error
            dump("Error: no se pudo crear el proceso secundario");
            exit(1);
        } elseif ($pid) {
            // Main process
            $this->setRoutes();
        } else {
            // Secondary process
            $this->grepRoutes();
            sleep(5);
            exit();
        }
    }

    private function setRoutes(): void
    {
        // Get routes file
        $data = $this->getRoutesFileContent();
        // Getting routes
        $encodedRoutes = $data["routes"];
        $routes = [];
        foreach ( $encodedRoutes as $routeName => $routeObject ) {
            $routes[$routeName] = new Route(...$routeObject);
        }

        $this->routes = $routes;
    }

    private function grepRoutes(): void
    {
        // Get routes file
        $data = $this->getRoutesFileContent();
        // Compare updated date
        $updated = $data["updated"];
        $dateUpdated = new DateTime($updated);
        $now = new DateTime();

        if ( abs($dateUpdated->getTimestamp() - $now->getTimestamp()) < 60 ) {
            // Stop updating routes
            // Routes are updated
            return;
        }

        // Updating routes
        $path = $this->server["DOCUMENT_ROOT"] ."/src/Controller/";
        $command = `grep -Enr "(@Route|public\sfunction)" {$path}`;
        exec($command);
        $lines = explode( "\r\n", $command );
        $grepLines = [];

        foreach ( $lines as $line ) {
            $grepLine = new GrepLine($line);
            $grepLines[] = $grepLine;
        }

        $couples = [];
        $lastGrepLine = null;
        foreach ( $grepLines as $grepLine ) {
            try {
                if ( $lastGrepLine instanceof GrepLine ) {
                    $lastIsRoute = $lastGrepLine->hasMatch("@Route");
                    $isFunction = $grepLine->hasMatch("function");
                    if ( $lastIsRoute && $isFunction ) {
                        $couples[] = [
                            "route" => $lastGrepLine,
                            "function" => $grepLine,
                        ];
                    }
                }
                $lastGrepLine = $grepLine;
            } catch(Exception $e) {
                $this->logger->warn("Error grepping routes...");
            }
        }

        $routes = [];
        foreach ( $couples as $couple ) {
            // Set pattern
            $rawPattern = trim($couple['route']->getMatchBetween($couple['route']->getContent(), '["', '"]'));
            $pattern = "^{$rawPattern}$";
            // Set controller
            $controllerName = trim($couple['function']->getMatchBetween($couple['function']->getFilename(), "src/Controller/", ".php"));
            $controller = str_replace( "/", "\\", $controllerName );
            // Set method
            $method = trim($couple['function']->getMatchBetween($couple['function']->getContent(), "function", "("));

            // Making a route
            $route = new Route($pattern, $controller, $method, []);
            $routes[] = $route->toArray();
        }
        $routesMatrix = [
            "updated" => date(self::DEFAULT_DATE_FORMAT),
            "routes" => $routes,
        ];

        $this->saveRoutesMatrix($routesMatrix);
    }

    private function getRoutesFileContent(): array
    {
        $routesFile = $this->server["DOCUMENT_ROOT"] ."/config/Routes.yaml";
        $stream = fopen($routesFile, "r");
        $content = fread($stream, filesize($routesFile));
        fclose($stream);
        return yaml_parse($content);
    }

    private function saveRoutesMatrix(array $routesMatrix): void
    {
        $routesFile = $this->server["DOCUMENT_ROOT"] ."/config/Routes.yaml";
        $stream = fopen($routesFile, "w");
        fwrite( $stream, yaml_emit($routesMatrix) );
        fclose($stream);
    }

    public function run(): void
    {
        $uri = explode( "?", $this->server["REQUEST_URI"] )[0];

        /** @var Route $route */
        foreach ( $this->routes as $route ) {
            $routePattern = str_replace( "/", "\\/", $route->getPattern() );
            $pattern = "/{$routePattern}/";
            preg_match($pattern, $uri, $matches);
            if ( is_array($matches) && !empty($matches) ) {
                $this->executeRoute($route);
                return;
            }
        }

        $route = new Route(...[
            "pattern" => "",
            "controller" => "Controller\NotFoundController",
            "method" => "render",
            "arguments" => [],
        ]);
        $this->executeRoute($route);
        die;

    }

    private function executeRoute(Route $route): void
    {
        $controllerName = "Controller\\". $route->getController();
        $method = $route->getMethod();
        $file = "../src/". str_replace("\\", "/", $controllerName) .".php";
        require_once $file;
        $this->logger->info("Route found. {$controllerName}::{$method}()");
        $controller = new $controllerName();
        print $controller->$method($this->request);
    }
}