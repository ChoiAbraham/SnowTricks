<?php

namespace App\Form\CustomConstraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueTrickTitle extends Constraint
{
    public $message = 'Le titre "{{ string }}" existe déjà';
}
