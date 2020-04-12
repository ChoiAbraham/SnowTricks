<?php


namespace App\Form\CustomConstraints;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NotInDatabase extends Constraint
{
    public $message = 'Mauvais identifiant';
}