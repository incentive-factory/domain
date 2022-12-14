<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Player\ValidRegistration;

use IncentiveFactory\Domain\Player\PlayerGateway;
use InvalidArgumentException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class RegistrationTokenExistsValidator extends ConstraintValidator
{
    public function __construct(private PlayerGateway $playerGateway)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof RegistrationTokenExists) {
            throw new InvalidArgumentException('Constraint must be RegistrationTokenExists.'); // @codeCoverageIgnore
        }

        if (!is_string($value) || '' === $value) {
            return; // @codeCoverageIgnore
        }

        if (!$this->playerGateway->hasRegistrationToken($value)) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
