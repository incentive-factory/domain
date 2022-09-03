<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Tests;

use IncentiveFactory\Domain\Tests\Application\Container\Container;
use PHPUnit\Framework\TestCase;

abstract class ContainerTestCase extends TestCase
{
    public static function createContainer(): Container
    {
        $container = new Container();

        $services = require 'services.php';

        $services($container);

        return $container;
    }
}
