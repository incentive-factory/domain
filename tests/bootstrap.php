<?php

declare(strict_types=1);

use IncentiveFactory\Game\Player\PlayerGateway;
use IncentiveFactory\Game\Player\Register\UniqueEmailValidator;
use IncentiveFactory\Game\Player\ValidRegistration\RegistrationTokenExistsValidator;
use IncentiveFactory\Game\Tests\Player\InMemoryPlayerRepository;
use Symfony\Component\DependencyInjection\Container;

require_once __DIR__.'/../vendor/autoload.php';

$container = new Container();

$container->set(InMemoryPlayerRepository::class, new InMemoryPlayerRepository());

/** @var PlayerGateway $playerGateway */
$playerGateway = $container->get(InMemoryPlayerRepository::class);

$container->set(UniqueEmailValidator::class, new UniqueEmailValidator($playerGateway));
$container->set(RegistrationTokenExistsValidator::class, new RegistrationTokenExistsValidator($playerGateway));
