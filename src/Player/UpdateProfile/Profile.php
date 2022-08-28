<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Player\UpdateProfile;

use IncentiveFactory\Game\Player\Player;
use IncentiveFactory\Game\Shared\Command\Command;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

#[UniqueEmail]
final class Profile implements Command
{
    #[Email]
    #[NotBlank]
    public string $email;

    #[NotBlank]
    public string $nickname;

    public ?string $avatar = null;

    public function __construct(public Player $player)
    {
    }
}
