<?php

namespace Core;

use Exception;

class GrepLine implements JsonSerializableInterface {

    private string $filename;
    private string $path;
    private string $name;
    private string $number;
    private string $content;

    public function __construct(string $line)
    {
        $splitLine = explode( ":", $line );
        if ( count($splitLine) < 3 ) {
            return;
        }
        $this->filename = $splitLine[0];
        $this->number = $splitLine[1];
        $this->content = $splitLine[2];

        $pathParts = explode( "/", $this->filename );
        $this->name = $pathParts[ array_key_last($pathParts) ];
        $this->path = str_replace( $this->name, "", $this->filename );
    }

    public function getFilename(): string
    {
        return $this->filename ?? "";
    }

    public function getPath(): string
    {
        return $this->path ?? "";
    }

    public function getName(): string
    {
        return $this->name ?? "";
    }

    public function getNumber(): string
    {
        return $this->number ?? "";
    }

    public function getContent(): string
    {
        return $this->content ?? "";
    }

    public function hasMatch(string $term): bool
    {
        return str_contains($this->getContent(), $term);
    }

    public function getMatchBetween(string $text, string $open, string $close): ?string
    {
        try {
            $first = strpos($text, $open) + strlen($open);
            $end = strpos($text, $close);
            return substr( $text, $first, ($end-$first) );
        } catch(Exception $e) {
            return null;
        }
    }

    public function toArray(): array
    {
        return [
            "filename" => $this->filename,
            "path" => $this->path,
            "name" => $this->name,
            "number" => $this->number,
            "content" => $this->content,
        ];
    }

    public function __toString(): string
    {
        return self::class.json_encode($this->toArray());
    }
}