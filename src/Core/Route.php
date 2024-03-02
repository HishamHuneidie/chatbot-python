<?php

namespace Core;

class Route implements JsonSerializableInterface
{

    public function __construct(
        private ?string $pattern,
        private ?string $controller,
        private ?string $method,
        private ?array  $arguments,
    ) {
    }

    public function getPattern(): ?string
    {
        return $this->pattern ?? null;
    }

    public function getController(): ?string
    {
        return $this->controller ?? null;
    }

    public function getMethod(): ?string
    {
        return $this->method ?? null;
    }

    public function getArguments(): ?array
    {
        return $this->arguments ?? [];
    }

    public function toArray(): array
    {
        return [
            "pattern"    => $this->getPattern(),
            "controller" => $this->getController(),
            "method"     => $this->getMethod(),
            "arguments"  => $this->getArguments(),
        ];
    }

    public function __toString(): string
    {
        return self::class.json_encode($this->toArray());
    }
}
