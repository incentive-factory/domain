<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Player\RequestForgottenPassword;

use IncentiveFactory\Game\Shared\Command\Command;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

final class ForgottenPasswordRequest implements Command
{
    #[Email]
    #[NotBlank]
    public string $email;
}
