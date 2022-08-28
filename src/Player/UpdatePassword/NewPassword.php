<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Player\UpdatePassword;

use IncentiveFactory\Game\Player\Player;
use IncentiveFactory\Game\Shared\Command\Command;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

#[CurrentPassword]
final class NewPassword implements Command
{
    #[NotBlank]
    public string $oldPassword;

    #[NotBlank]
    #[Regex(pattern: '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/')]
    public string $plainPassword;

    public function __construct(public Player $player)
    {
    }
}
