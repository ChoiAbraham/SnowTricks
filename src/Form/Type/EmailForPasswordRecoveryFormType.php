<?php


namespace App\Form\Type;

use App\Domain\DTO\EmailPasswordRecoveryDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmailForPasswordRecoveryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo', TextType::class, [
                'required' => false,
            ])
            ->add('email', TextType::class, [
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => EmailPasswordRecoveryDTO::class,
                'empty_data' => function (FormInterface $form) {
                    return new EmailPasswordRecoveryDTO(
                        $form->get('pseudo')->getData(),
                        $form->get('email')->getData()
                    );
                }
            ]
        );
    }
}