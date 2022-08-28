<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Player\UpdatePassword;

use InvalidArgumentException;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class CurrentPasswordValidator extends ConstraintValidator
{
    public function __construct(private PasswordHasherInterface $passwordHasher)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof CurrentPassword) {
            throw new InvalidArgumentException('The constraint must be an instance of CurrentPassword.'); // @codeCoverageIgnore
        }

        if (!$value instanceof NewPassword) {
            return; // @codeCoverageIgnore
        }

        $currentPassword = $value->player->password();

        if (!$this->passwordHasher->verify($currentPassword, $value->oldPassword)) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
