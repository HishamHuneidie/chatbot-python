<?php

namespace Core;
use DateTime;
use Exception;

class Request {

    private const DEFAULT_DATE_FORMAT = "Y-m-d H:i:s";
    private string $path;
    private array $get;
    private array $post;
    private array $cookies;
    private string $content;
    private string $scheme;
    private string $method;
    private string $agent;
    private DateTime $time;
    private static $instance;

    private function __construct()
    {
        $this->path = explode( "?", $_SERVER['REQUEST_URI'] )[0];
        $this->get = $_GET;
        $this->post = $_POST;
        $this->cookies = $_COOKIE;
        $this->processContent();
        $this->scheme = $_SERVER['REQUEST_SCHEME'];
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->agent = $_SERVER['HTTP_USER_AGENT'];
        $this->processDate();
    }

    private function processDate(): void
    {
        $timestamp = (int)$_SERVER['REQUEST_TIME'] * 100;
        $date = date(self::DEFAULT_DATE_FORMAT, $timestamp);
        $this->time = new DateTime($date);
    }

    private function processContent(): void
    {
        try {
            $requestBody = file_get_contents('php://input');
            $this->content = $requestBody;
        } catch(Exception $e) {
            $this->content = "";
        }
    }

    public static function getInstance(): static
    {
        if ( self::$instance === null ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getGet(): array
    {
        return $this->get;
    }

    public function getPost(): array
    {
        return $this->post;
    }

    public function getCookies(): array
    {
        return $this->cookies;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getScheme(): string
    {
        return $this->scheme;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getAgent(): string
    {
        return $this->agent;
    }

    public function getTime(): DateTime
    {
        return $this->time;
    }
}