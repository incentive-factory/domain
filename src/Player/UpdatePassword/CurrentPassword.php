<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Player\UpdatePassword;

use Attribute;
use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

#[Attribute(flags: Attribute::TARGET_CLASS)]
final class CurrentPassword extends Constraint
{
    public string $message = 'This password is not correct.';

    #[HasNamedArguments]
    public function __construct(
        public string $property,
        public string $target,
        public string $targetProperty,
        public ?string $expression = null,
        array $groups = null,
        mixed $payload = null
    ) {
        parent::__construct([], $groups, $payload);
    }

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
