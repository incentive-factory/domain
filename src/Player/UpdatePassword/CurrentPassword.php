<?php

declare(strict_types=1);

namespace IncentiveFactory\Domain\Player\UpdatePassword;

use Attribute;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

#[Attribute(flags: Attribute::TARGET_CLASS)]
final class CurrentPassword extends Constraint
{
    public string $message = 'This password is not correct.';

    /**
     * @return class-string<ConstraintValidator>
     */
    public function validatedBy(): string
    {
        return CurrentPasswordValidator::class;
    }

    public function getTargets(): string
    {
        return parent::CLASS_CONSTRAINT;
    }
}
