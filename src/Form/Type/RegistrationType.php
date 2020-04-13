<?php

namespace App\Form\Type;

use App\Domain\DTO\CreateUserDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('email')
            ->add('password', PasswordType::class)
            ->add('confirm_password', PasswordType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => CreateUserDTO::class,
                'empty_data' => function (FormInterface $form) {
                    return new CreateUserDTO(
                        $form->get('username')->getData(),
                        $form->get('email')->getData(),
                        $form->get('password')->getData(),
                        $form->get('confirm_password')->getData()
                    );
                }
            ]
        );
    }

    public function getSalt()
    {
    }
}