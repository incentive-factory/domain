<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Player\Register;

use IncentiveFactory\Domain\Player\Gender;
use IncentiveFactory\Domain\Shared\Command\Command;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

final class Registration implements Command
{
    #[Email]
    #[NotBlank]
    #[UniqueEmail]
    public string $email;

    public Gender $gender;

    #[NotBlank]
    public string $nickname;

    #[NotBlank]
    #[Regex(pattern: '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/')]
    public string $plainPassword;
}
