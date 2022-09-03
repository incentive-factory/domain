<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Player\UpdateProfile;

use IncentiveFactory\Domain\Player\PlayerGateway;
use InvalidArgumentException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class UniqueEmailValidator extends ConstraintValidator
{
    public function __construct(private PlayerGateway $playerGateway)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueEmail) {
            throw new InvalidArgumentException('The constraint must be an instance of UniqueEmail.'); // @codeCoverageIgnore
        }

        if (!$value instanceof Profile) {
            return; // @codeCoverageIgnore
        }

        if ('' === $value->email) {
            return; // @codeCoverageIgnore
        }

        if ($value->player->email() === $value->email) {
            return; // @codeCoverageIgnore
        }

        if ($this->playerGateway->hasEmail($value->email, $value->player)) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
