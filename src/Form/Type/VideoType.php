<?php

namespace App\Form\Type;

use App\Domain\DTO\TrickVideoDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pathUrl', TextType::class, [
                'label' => 'Video',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => TrickVideoDTO::class,
                'empty_data' => function (FormInterface $form) {
                    return new TrickVideoDTO(
                        $form->get('pathUrl')->getData(),
                    );
                },
                'translation_domain' => 'form_add_video'
            ]
        );
    }
}