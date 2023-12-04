<?php

// phpcs:disable CDS.Classes.OverloadedDeclaration.IncompatibleMethod
// phpcs:disable PHPCompatibility.FunctionDeclarations.NewReturnTypeDeclarations.voidFound

namespace CommunityDS\Deputy\Api\Tests\Bridge;

use CommunityDS\Deputy\Api\Tests\TestCaseInterface;
use PHPUnit\Framework\TestCase;

abstract class BridgeTestCase extends TestCase implements TestCaseInterface
{
    protected function tearDown(): void
    {
        parent::tearDown();
        $this->tearDownCustom();
    }
}

// phpcs:enable
