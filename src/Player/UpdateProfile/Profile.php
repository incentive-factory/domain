<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Player\UpdateProfile;

use IncentiveFactory\Domain\Player\Gender;
use IncentiveFactory\Domain\Player\Player;
use IncentiveFactory\Domain\Shared\Command\Command;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

#[UniqueEmail]
final class Profile implements Command
{
    #[Email]
    #[NotBlank]
    public string $email;

    public Gender $gender;

    #[NotBlank]
    public string $nickname;

    public ?string $avatar = null;

    public function __construct(public Player $player)
    {
        $this->email = $player->email();
        $this->nickname = $player->nickname();
        $this->gender = $player->gender();
        $this->avatar = $player->avatar();
    }
}
