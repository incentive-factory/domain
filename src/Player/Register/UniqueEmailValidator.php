<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Player\Register;

use IncentiveFactory\Game\Player\PlayerGateway;
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
            throw new InvalidArgumentException('Constraint must be UniqueEmail.'); // @codeCoverageIgnore
        }

        if (!is_string($value) || '' === $value) {
            return;
        }

        if ($this->playerGateway->hasEmail($value)) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
