<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Shared\Query;

interface QueryBus
{
    public function fetch(Query $query): void;
}
