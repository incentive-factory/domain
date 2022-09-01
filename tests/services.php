<?php

declare(strict_types=1);

use IncentiveFactory\Game\Path\GetTrainingBySlug\GetTrainingBySlug;
use IncentiveFactory\Game\Path\GetTrainingBySlug\TrainingSlug;
use IncentiveFactory\Game\Path\GetTranings\GetTrainings;
use IncentiveFactory\Game\Path\GetTranings\ListOfTrainings;
use IncentiveFactory\Game\Path\TrainingGateway;
use IncentiveFactory\Game\Player\CreateRegistrationToken\CreateRegistrationToken;
use IncentiveFactory\Game\Player\GetPlayerByForgottenPasswordToken\ForgottenPasswordToken;
use IncentiveFactory\Game\Player\GetPlayerByForgottenPasswordToken\GetPlayerByForgottenPasswordToken;
use IncentiveFactory\Game\Player\PlayerGateway;
use IncentiveFactory\Game\Player\Register\NewRegistration;
use IncentiveFactory\Game\Player\Register\Register;
use IncentiveFactory\Game\Player\Register\Registration;
use IncentiveFactory\Game\Player\Register\UniqueEmailValidator as RegisterUniqueEmailValidator;
use IncentiveFactory\Game\Player\RequestForgottenPassword\ForgottenPasswordRequest;
use IncentiveFactory\Game\Player\RequestForgottenPassword\RequestForgottenPassword;
use IncentiveFactory\Game\Player\ResetPassword\NewPassword as ResetPasswordNewPassword;
use IncentiveFactory\Game\Player\ResetPassword\ResetPassword;
use IncentiveFactory\Game\Player\UpdatePassword\CurrentPasswordValidator;
use IncentiveFactory\Game\Player\UpdatePassword\NewPassword as UpdatePasswordNewPassword;
use IncentiveFactory\Game\Player\UpdatePassword\UpdatePassword;
use IncentiveFactory\Game\Player\UpdateProfile\Profile;
use IncentiveFactory\Game\Player\UpdateProfile\UniqueEmailValidator as UpdateProfileUniqueEmailValidator;
use IncentiveFactory\Game\Player\UpdateProfile\UpdateProfile;
use IncentiveFactory\Game\Player\ValidRegistration\RegistrationTokenExistsValidator;
use IncentiveFactory\Game\Player\ValidRegistration\ValidationOfRegistration;
use IncentiveFactory\Game\Player\ValidRegistration\ValidRegistration;
use IncentiveFactory\Game\Shared\Command\CommandBus;
use IncentiveFactory\Game\Shared\Event\EventBus;
use IncentiveFactory\Game\Shared\Query\QueryBus;
use IncentiveFactory\Game\Shared\Uid\UlidGeneratorInterface;
use IncentiveFactory\Game\Shared\Uid\UuidGeneratorInterface;
use IncentiveFactory\Game\Tests\Application\Container\Container;
use IncentiveFactory\Game\Tests\Application\CQRS\TestCommandBus;
use IncentiveFactory\Game\Tests\Application\CQRS\TestEventBus;
use IncentiveFactory\Game\Tests\Application\CQRS\TestQueryBus;
use IncentiveFactory\Game\Tests\Application\Repository\InMemoryTrainingRepository;
use IncentiveFactory\Game\Tests\Application\Repository\InMemoryPlayerRepository;
use IncentiveFactory\Game\Tests\Application\Uid\UlidGenerator;
use IncentiveFactory\Game\Tests\Application\Uid\UuidGenerator;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

return function (Container $container): void {
    $container
        ->set(
            GetTrainingBySlug::class,
            static fn (Container $container): GetTrainingBySlug => new GetTrainingBySlug(
                $container->get(TrainingGateway::class)
            )
        )
        ->set(
            GetTrainings::class,
            static fn (Container $container): GetTrainings => new GetTrainings(
                $container->get(TrainingGateway::class)
            )
        )
        ->set(
            GetPlayerByForgottenPasswordToken::class,
            static fn (Container $container): GetPlayerByForgottenPasswordToken => new GetPlayerByForgottenPasswordToken(
                $container->get(PlayerGateway::class)
            )
        )
        ->set(
            Register::class,
            static fn (Container $container): Register => new Register(
                $container->get(PasswordHasherInterface::class),
                $container->get(UlidGeneratorInterface::class),
                $container->get(PlayerGateway::class),
                $container->get(EventBus::class)
            )
        )
        ->set(
            RequestForgottenPassword::class,
            static fn (Container $container): RequestForgottenPassword => new RequestForgottenPassword(
                $container->get(UuidGeneratorInterface::class),
                $container->get(PlayerGateway::class),
                $container->get(EventBus::class)
            )
        )
        ->set(
            ResetPassword::class,
            static fn (Container $container): ResetPassword => new ResetPassword(
                $container->get(PasswordHasherInterface::class),
                $container->get(PlayerGateway::class)
            )
        )
        ->set(
            UpdatePassword::class,
            static fn (Container $container): UpdatePassword => new UpdatePassword(
                $container->get(PasswordHasherInterface::class),
                $container->get(PlayerGateway::class)
            )
        )
        ->set(
            UpdateProfile::class,
            static fn (Container $container): UpdateProfile => new UpdateProfile(
                $container->get(PlayerGateway::class)
            )
        )
        ->set(
            ValidRegistration::class,
            static fn (Container $container): ValidRegistration => new ValidRegistration(
                $container->get(PlayerGateway::class)
            )
        )
        ->set(
            PlayerGateway::class,
            static fn (Container $container): PlayerGateway => new InMemoryPlayerRepository()
        )
        ->set(
            TrainingGateway::class,
            static fn (Container $container): TrainingGateway => new InMemoryTrainingRepository()
        )
        ->set(
            UuidGeneratorInterface::class,
            static fn (Container $container): UuidGeneratorInterface => new UuidGenerator()
        )
        ->set(
            UlidGeneratorInterface::class,
            static fn (Container $container): UlidGeneratorInterface => new UlidGenerator()
        )
        ->set(
            UpdateProfileUniqueEmailValidator::class,
            static fn (Container $container): UpdateProfileUniqueEmailValidator => new UpdateProfileUniqueEmailValidator(
                $container->get(PlayerGateway::class)
            )
        )
        ->set(
            RegisterUniqueEmailValidator::class,
            static fn (Container $container): RegisterUniqueEmailValidator => new RegisterUniqueEmailValidator(
                $container->get(PlayerGateway::class)
            )
        )
        ->set(
            CurrentPasswordValidator::class,
            static fn (Container $container): CurrentPasswordValidator => new CurrentPasswordValidator(
                $container->get(PasswordHasherInterface::class)
            )
        )
        ->set(
            RegistrationTokenExistsValidator::class,
            static fn (Container $container): RegistrationTokenExistsValidator => new RegistrationTokenExistsValidator(
                $container->get(PlayerGateway::class)
            )
        )
        ->set(
            PasswordHasherInterface::class,
            static fn (Container $container): PasswordHasherInterface => (new PasswordHasherFactory(['common' => ['algorithm' => 'plaintext']]))
                ->getPasswordHasher('common')
        )
        ->set(
            CreateRegistrationToken::class,
            static fn (Container $container): CreateRegistrationToken => new CreateRegistrationToken(
                $container->get(UuidGeneratorInterface::class),
                $container->get(PlayerGateway::class)
            )
        )
        ->set(
            EventBus::class,
            static fn (Container $container): EventBus => new TestEventBus([
                NewRegistration::class => $container->get(CreateRegistrationToken::class),
            ])
        )
        ->set(
            QueryBus::class,
            static fn (Container $container): QueryBus => new TestQueryBus($container, [
                ForgottenPasswordToken::class => GetPlayerByForgottenPasswordToken::class,
                ListOfTrainings::class => GetTrainings::class,
                TrainingSlug::class => GetTrainingBySlug::class,
            ])
        )
        ->set(
            CommandBus::class,
            static fn (Container $container): CommandBus => new TestCommandBus($container, [
                Registration::class => Register::class,
                ValidationOfRegistration::class => ValidRegistration::class,
                ForgottenPasswordRequest::class => RequestForgottenPassword::class,
                ResetPasswordNewPassword::class => ResetPassword::class,
                Profile::class => UpdateProfile::class,
                UpdatePasswordNewPassword::class => UpdatePassword::class,
            ])
        )
    ;
};
