<?php

declare(strict_types=1);

namespace IncentiveFactory\Game\Player\UpdatePassword;

use InvalidArgumentException;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
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

        if (!is_object($value)) {
            throw new InvalidArgumentException('The value must not be null.'); // @codeCoverageIgnore
        }

        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        /** @var ?string $oldPassword */
        $oldPassword = $propertyAccessor->getValue($value, $constraint->property);

        if (null === $oldPassword || '' === $oldPassword) {
            return; // @codeCoverageIgnore
        }

        /** @var ?object $object */
        $object = $propertyAccessor->getValue($value, $constraint->target);

        if (null === $object) {
            return; // @codeCoverageIgnore
        }

        /** @var string $currentPassword */
        $currentPassword = $propertyAccessor->getValue($object, $constraint->targetProperty);

        if (!$this->passwordHasher->verify($currentPassword, $oldPassword)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
