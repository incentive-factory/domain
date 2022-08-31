<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Tests\Application\Container;

use Closure;
use Psr\Container\ContainerInterface;
use RuntimeException;

/**
 * @template T
 */
final class Container implements ContainerInterface
{
    /**
     * @var array<class-string<T>, Closure>
     */
    private array $services = [];

    /**
     * @var array<class-string<T>, T>
     */
    private array $instances = [];

    /**
     * @return Container<T>
     */
    public function set(string $id, Closure $callback): self
    {
        if (!class_exists($id) && !interface_exists($id)) {
            throw new RuntimeException(sprintf('"%s" does not exist', $id));
        }

        /**
         * @var class-string<T> $id
         */
        if (!$this->has($id)) {
            $this->services[$id] = $callback;
        }

        return $this;
    }

    /**
     * @return T
     */
    public function get(string $id)
    {
        if (!class_exists($id) && !interface_exists($id)) {
            throw new RuntimeException(sprintf('"%s" does not exist', $id));
        }

        /**
         * @var class-string<T> $id
         */
        if (!isset($this->instances[$id])) {
            if (!isset($this->services[$id])) {
                throw new RuntimeException('Service not found: '.$id);
            }
            $this->instances[$id] = $this->services[$id]($this);
        }

        return $this->instances[$id];
    }

    public function has(string $id): bool
    {
        return isset($this->services[$id]);
    }
}
