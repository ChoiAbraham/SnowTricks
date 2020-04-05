<?php


namespace App\Form\CustomConstraints;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueEmail extends Constraint
{
    public $message = 'L\'email "{{ string }}" existe déjà';
}