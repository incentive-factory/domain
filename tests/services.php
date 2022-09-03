<?php

declare(strict_types=1);

use IncentiveFactory\Domain\Course\BeginCourse\BeginCourse;
use IncentiveFactory\Domain\Course\BeginCourse\BeginningOfCourse;
use IncentiveFactory\Domain\Course\CompleteCourse\CompleteCourse;
use IncentiveFactory\Domain\Course\CompleteCourse\CompletingOfCourse;
use IncentiveFactory\Domain\Course\CourseGateway;
use IncentiveFactory\Domain\Course\CourseLogGateway;
use IncentiveFactory\Domain\Course\GetCourseBySlug\CourseSlug;
use IncentiveFactory\Domain\Course\GetCourseBySlug\GetCourseBySlug;
use IncentiveFactory\Domain\Path\BeginTraining\BeginningOfTraining;
use IncentiveFactory\Domain\Path\BeginTraining\BeginTraining;
use IncentiveFactory\Domain\Path\GetPathsByPlayer\GetPathsByPlayer;
use IncentiveFactory\Domain\Path\GetPathsByPlayer\PlayerPaths;
use IncentiveFactory\Domain\Path\GetTrainingBySlug\GetTrainingBySlug;
use IncentiveFactory\Domain\Path\GetTrainingBySlug\TrainingSlug;
use IncentiveFactory\Domain\Path\GetTranings\GetTrainings;
use IncentiveFactory\Domain\Path\GetTranings\ListOfTrainings;
use IncentiveFactory\Domain\Path\PathGateway;
use IncentiveFactory\Domain\Path\TrainingGateway;
use IncentiveFactory\Domain\Player\GetPlayerByForgottenPasswordToken\ForgottenPasswordToken;
use IncentiveFactory\Domain\Player\GetPlayerByForgottenPasswordToken\GetPlayerByForgottenPasswordToken;
use IncentiveFactory\Domain\Player\PlayerGateway;
use IncentiveFactory\Domain\Player\Register\Register;
use IncentiveFactory\Domain\Player\Register\Registration;
use IncentiveFactory\Domain\Player\Register\UniqueEmailValidator as RegisterUniqueEmailValidator;
use IncentiveFactory\Domain\Player\RequestForgottenPassword\ForgottenPasswordRequest;
use IncentiveFactory\Domain\Player\RequestForgottenPassword\RequestForgottenPassword;
use IncentiveFactory\Domain\Player\ResetPassword\NewPassword as ResetPasswordNewPassword;
use IncentiveFactory\Domain\Player\ResetPassword\ResetPassword;
use IncentiveFactory\Domain\Player\UpdatePassword\CurrentPasswordValidator;
use IncentiveFactory\Domain\Player\UpdatePassword\NewPassword as UpdatePasswordNewPassword;
use IncentiveFactory\Domain\Player\UpdatePassword\UpdatePassword;
use IncentiveFactory\Domain\Player\UpdateProfile\Profile;
use IncentiveFactory\Domain\Player\UpdateProfile\UniqueEmailValidator as UpdateProfileUniqueEmailValidator;
use IncentiveFactory\Domain\Player\UpdateProfile\UpdateProfile;
use IncentiveFactory\Domain\Player\ValidRegistration\RegistrationTokenExistsValidator;
use IncentiveFactory\Domain\Player\ValidRegistration\ValidationOfRegistration;
use IncentiveFactory\Domain\Player\ValidRegistration\ValidRegistration;
use IncentiveFactory\Domain\Shared\Command\CommandBus;
use IncentiveFactory\Domain\Shared\Event\EventBus;
use IncentiveFactory\Domain\Shared\Query\QueryBus;
use IncentiveFactory\Domain\Shared\Uid\UlidGeneratorInterface;
use IncentiveFactory\Domain\Shared\Uid\UuidGeneratorInterface;
use IncentiveFactory\Domain\Tests\Application\Container\Container;
use IncentiveFactory\Domain\Tests\Application\CQRS\TestCommandBus;
use IncentiveFactory\Domain\Tests\Application\CQRS\TestEventBus;
use IncentiveFactory\Domain\Tests\Application\CQRS\TestQueryBus;
use IncentiveFactory\Domain\Tests\Application\Repository\InMemoryCourseLogRepository;
use IncentiveFactory\Domain\Tests\Application\Repository\InMemoryCourseRepository;
use IncentiveFactory\Domain\Tests\Application\Repository\InMemoryPathRepository;
use IncentiveFactory\Domain\Tests\Application\Repository\InMemoryPlayerRepository;
use IncentiveFactory\Domain\Tests\Application\Repository\InMemoryTrainingRepository;
use IncentiveFactory\Domain\Tests\Application\Uid\UlidGenerator;
use IncentiveFactory\Domain\Tests\Application\Uid\UuidGenerator;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

return function (Container $container): void {
    $container
        ->set(
            BeginTraining::class,
            static fn (Container $container): BeginTraining => new BeginTraining(
                $container->get(PathGateway::class),
                $container->get(UlidGeneratorInterface::class),
                $container->get(EventBus::class),
            )
        )
        ->set(
            CompleteCourse::class,
            static fn (Container $container): CompleteCourse => new CompleteCourse(
                $container->get(CourseLogGateway::class),
                $container->get(EventBus::class),
            )
        )
        ->set(
            BeginCourse::class,
            static fn (Container $container): BeginCourse => new BeginCourse(
                $container->get(CourseLogGateway::class),
                $container->get(UlidGeneratorInterface::class),
                $container->get(EventBus::class),
            )
        )
        ->set(
            GetPathsByPlayer::class,
            static fn (Container $container): GetPathsByPlayer => new GetPathsByPlayer(
                $container->get(PathGateway::class)
            )
        )
        ->set(
            GetCourseBySlug::class,
            static fn (Container $container): GetCourseBySlug => new GetCourseBySlug(
                $container->get(CourseGateway::class)
            )
        )
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
                $container->get(UuidGeneratorInterface::class),
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
            CourseLogGateway::class,
            static fn (Container $container): CourseLogGateway => new InMemoryCourseLogRepository()
        )
        ->set(
            CourseGateway::class,
            static fn (Container $container): CourseGateway => new InMemoryCourseRepository()
        )
        ->set(
            PlayerGateway::class,
            static fn (Container $container): PlayerGateway => new InMemoryPlayerRepository()
        )
        ->set(
            PathGateway::class,
            static fn (Container $container): PathGateway => new InMemoryPathRepository()
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
            EventBus::class,
            static fn (Container $container): EventBus => new TestEventBus()
        )
        ->set(
            QueryBus::class,
            static fn (Container $container): QueryBus => new TestQueryBus($container, [
                ForgottenPasswordToken::class => GetPlayerByForgottenPasswordToken::class,
                ListOfTrainings::class => GetTrainings::class,
                TrainingSlug::class => GetTrainingBySlug::class,
                PlayerPaths::class => GetPathsByPlayer::class,
                CourseSlug::class => GetCourseBySlug::class,
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
                BeginningOfTraining::class => BeginTraining::class,
                BeginningOfCourse::class => BeginCourse::class,
                CompletingOfCourse::class => CompleteCourse::class,
            ])
        )
    ;
};
