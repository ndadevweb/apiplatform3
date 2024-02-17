<?php

namespace App\Validator;

use App\Entity\DragonTreasure;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TreasuresAllowedOwnerChangeValidator extends ConstraintValidator
{
    public function __construct(private EntityManagerInterface $entityManger)
    {}

    public function validate($value, Constraint $constraint)
    {
        assert($constraint instanceof TreasuresAllowedOwnerChange);
    
        if (null === $value || '' === $value) {
            return;
        }

        assert($value instanceof Collection);

        $unitOfWork = $this->entityManger->getUnitOfWork();
        foreach($value as $dragonTreasure) {
            assert($dragonTreasure instanceof DragonTreasure);

            $originalData = $unitOfWork->getOriginalEntityData($dragonTreasure);
            $originalOwnerId = $originalData['owner_id'];
            $newOwnerId = $dragonTreasure->getOwner()->getId();

            if(!$originalOwnerId || $originalOwnerId === $newOwnerId) {
                return;
            }

            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }


    }
}
