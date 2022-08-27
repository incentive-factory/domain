<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Player;

use IncentiveFactory\Game\Shared\Command\Command;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

final class Registration implements Command
{
    #[Email]
    #[NotBlank]
    public string $email;

    #[NotBlank]
    public string $nickname;

    #[NotBlank]
    #[Regex(pattern: '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/')]
    public string $plainPassword;
}
