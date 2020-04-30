<?php

namespace App\Form\Type;

use App\Domain\DTO\ProfilPictureDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ProfilPictureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'profilPicture',
                FileType::class,
                [
                    'attr' => ['placeholder' => 'Avatar', 'lang' => 'fr'],
                    'label' => false,
                    'required' => false,
                    'constraints' => [
                        new File(
                            [
                                'maxSize' => '1024k',
                                'mimeTypes' => [
                                    'image/png',
                                    'image/jpeg',
                                    'image/jpg',
                                ],
                                'mimeTypesMessage' => 'Seuls les formats .png .jpg .jpeg sont acceptés',
                            ]
                        ),
                    ],
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => ProfilPictureDTO::class,
                'empty_data' => function (FormInterface $form) {
                    return new ProfilPictureDTO(
                        $form->get('profilPicture')->getData()
                    );
                },
            ]
        );
    }
}
