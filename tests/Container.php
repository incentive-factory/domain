<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Tests;

use InvalidArgumentException;
use Psr\Container\ContainerInterface;

final class Container implements ContainerInterface
{
    /**
     * @var array<class-string, object>
     */
    private array $services = [];

    public function register(object $service): self
    {
        $this->services[$service::class] = $service;

        return $this;
    }

    public function get(string $id): mixed
    {
        if (false === isset($this->services[$id])) {
            throw new InvalidArgumentException(sprintf('Service "%s" not found', $id));
        }

        return $this->services[$id];
    }

    public function has(string $id): bool
    {
        return isset($this->services[$id]);
    }
}
