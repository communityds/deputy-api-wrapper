<?php

namespace CommunityDS\Deputy\Api\Adapter\Logger;

use CommunityDS\Deputy\Api\Adapter\LoggerInterface;
use CommunityDS\Deputy\Api\Component;

/**
 * Logger that wraps around a PSR-3 compatible logger.
 *
 * @see https://github.com/php-fig/log
 */
class Psr3Logger extends Component implements LoggerInterface
{
    /**
     * PSR-3 Compatible logger
     *
     * @var \Psr\Log\LoggerInterface $logger
     */
    protected $logger;

    public function emergency($message, array $context = [])
    {
        $this->logger->emergency($message, $context);
    }

    public function alert($message, array $context = [])
    {
        $this->logger->alert($message, $context);
    }

    public function critical($message, array $context = [])
    {
        $this->logger->critical($message, $context);
    }

    public function error($message, array $context = [])
    {
        $this->logger->error($message, $context);
    }

    public function warning($message, array $context = [])
    {
        $this->logger->warning($message, $context);
    }

    public function notice($message, array $context = [])
    {
        $this->logger->notice($message, $context);
    }

    public function info($message, array $context = [])
    {
        $this->logger->info($message, $context);
    }

    public function debug($message, array $context = [])
    {
        $this->logger->debug($message, $context);
    }

    public function log($level, $message, array $context)
    {
        $this->logger->log($message, $context);
    }
}
