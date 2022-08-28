<?php

declare(strict_types=1);

use IncentiveFactory\Game\Player\PlayerGateway;
use IncentiveFactory\Game\Player\Register\UniqueEmailValidator;
use IncentiveFactory\Game\Player\UpdatePassword\CurrentPasswordValidator;
use IncentiveFactory\Game\Player\UpdateProfile\UniqueEmailValidator as UpdateProfileUniqueEmailValidator;
use IncentiveFactory\Game\Player\ValidRegistration\RegistrationTokenExistsValidator;
use IncentiveFactory\Game\Tests\Player\InMemoryPlayerRepository;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;

require_once __DIR__.'/../vendor/autoload.php';

$container = new Container();

$container->set(InMemoryPlayerRepository::class, new InMemoryPlayerRepository());

/** @var PlayerGateway $playerGateway */
$playerGateway = $container->get(InMemoryPlayerRepository::class);

$container->set(UniqueEmailValidator::class, new UniqueEmailValidator($playerGateway));
$container->set(UpdateProfileUniqueEmailValidator::class, new UpdateProfileUniqueEmailValidator($playerGateway));
$container->set(
    CurrentPasswordValidator::class,
    new CurrentPasswordValidator(
        (new PasswordHasherFactory(['common' => ['algorithm' => 'plaintext']]))
            ->getPasswordHasher('common')
    )
);
$container->set(RegistrationTokenExistsValidator::class, new RegistrationTokenExistsValidator($playerGateway));
