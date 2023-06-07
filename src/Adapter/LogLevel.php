<?php

namespace CommunityDS\Deputy\Api\Adapter;

/**
 * Describes log levels.
 *
 * Required for PHP 5.6 support.
 */
class LogLevel
{
    const EMERGENCY = 'emergency';
    const ALERT     = 'alert';
    const CRITICAL  = 'critical';
    const ERROR     = 'error';
    const WARNING   = 'warning';
    const NOTICE    = 'notice';
    const INFO      = 'info';
    const DEBUG     = 'debug';
}
