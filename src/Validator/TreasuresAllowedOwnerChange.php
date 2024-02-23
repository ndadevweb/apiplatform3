<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
class TreasuresAllowedOwnerChange extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'You cannot change treasure owner.';

    public function getTargets(): string|array
    {
        return self::CLASS_CONSTRAINT;
    }
}
