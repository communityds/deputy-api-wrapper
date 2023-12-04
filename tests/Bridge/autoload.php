<?php

spl_autoload_register(
    function ($class) {
        if ($class == 'CommunityDS\Deputy\Api\Tests\Bridge\BridgeTestCase') {
            if (PHP_MAJOR_VERSION < 8) {
                require_once __DIR__ . '/Php56/BridgeTestCase.php';
            } else {
                require_once __DIR__ . '/Php81/BridgeTestCase.php';
            }
        }
    }
);
