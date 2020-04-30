<?php

namespace App\Form\CustomConstraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueUsername extends Constraint
{
    public $message = 'Le nom "{{ string }}" existe déjà';
}
