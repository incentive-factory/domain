<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Player\Register;

use Attribute;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

#[Attribute]
final class UniqueEmail extends Constraint
{
    public string $message = 'This email is already used.';

    /**
     * @return class-string<ConstraintValidator>
     */
    public function validatedBy(): string
    {
        return UniqueEmailValidator::class;
    }
}
