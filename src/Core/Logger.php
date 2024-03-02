<?php

namespace Core;

class Logger {

    private const LOG_LEVEL_INFO = "INFO";
    private const LOG_LEVEL_WARNING = "WARNING";
    private const LOG_LEVEL_ALERT = "ALERT";
    private const LOG_LEVEL_DEBUG = "DEBUG";
    private array $server;

    public function __construct(array $server)
    {
        $this->server = $server;
    }

    private function getDate(): string
    {
        return date("[Y-m-d H:i:s]");
    }

    private function processLog(string|int|array $message): string
    {
        if ( is_int($message) ) {
            return "{$message}";
        }

        if ( is_array($message) ) {
            return json_encode($message);
        }

        return $message;
    }

    private function log(string $logLevel, string $message): void
    {
        // Setting log
        $date = $this->getDate();
        $log = "{$date} {$logLevel} {$message}";
        // Saving log
        $file = $this->server["DOCUMENT_ROOT"] ."/var/log/dev.log";
        $stream = fopen( $file, "a" );
        fwrite( $stream, "\r\n{$log}" );
        fclose($stream);
    }

    public function info(string|int|array $message): void
    {
        $this->log(self::LOG_LEVEL_INFO, $this->processLog($message));
    }

    public function warn(string|int|array $message): void
    {
        $this->log(self::LOG_LEVEL_WARNING, $this->processLog($message));
    }

    public function alert(string|int|array $message): void
    {
        $this->log(self::LOG_LEVEL_ALERT, $this->processLog($message));
    }

    public function debug(string|int|array $message): void
    {
        $this->log(self::LOG_LEVEL_DEBUG, $this->processLog($message));
    }
}