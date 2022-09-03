<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Player\UpdateProfile;

use Attribute;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

#[Attribute(flags: Attribute::TARGET_CLASS)]
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

    public function getTargets(): string
    {
        return parent::CLASS_CONSTRAINT;
    }
}
