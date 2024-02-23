<?php

namespace App\Validator;

use App\ApiResource\UserApi;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TreasuresAllowedOwnerChangeValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        assert($constraint instanceof TreasuresAllowedOwnerChange);
    
        if (null === $value || '' === $value) {
            return;
        }

        assert($value instanceof UserApi);

        foreach($value->dragonTreasures as $dragonTreasureApi) {
            $originalOwnerId = $dragonTreasureApi->owner?->id;
            $newOwnerId = $value->id;

            if(!$originalOwnerId || $originalOwnerId === $newOwnerId) {
                return;
            }

            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }


    }
}
