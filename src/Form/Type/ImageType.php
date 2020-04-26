<?php

namespace App\Form\Type;

use App\Domain\DTO\TrickImageDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id',
                HiddenType::class,
                [
                    'required' => false,
                    'mapped' => false,
                    'label' => false,
                ]
            )
            ->add(
                'image',
                FileType::class,
                [
                    'attr' => ['placeholder' => 'Trick Image', 'lang' => 'fr'],
                    'label' => 'Image',
                    'required' => false,
                    'constraints' => [
                        new File(
                            [
                                'maxSize' => '1024k',
                                'mimeTypes' => [
                                    "image/png",
                                    "image/jpeg",
                                    "image/jpg",
                                    "image/gif",
                                ],
                                'mimeTypesMessage' => 'Veuillez uploader un fichier conforme',
                            ]
                        )
                    ]
                ]
            )
            ->add('alt', TextType::class, ['label' => 'Description de l\'image'])
            ->add(
                'first_image',
                CheckboxType::class,
                [
                    'attr' => ['class' => 'checkbox_check', 'data-id' => '__name__'],
                    'label' => 'Image à la Une ?',
                    'required' => false
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TrickImageDTO::class,
            'empty_data' => function (FormInterface $form) {
                return new TrickImageDTO(
                    $form->get('id')->getData(),
                    $form->get('alt')->getData(),
                    $form->get('image')->getData(),
                    $form->get('first_image')->getData()
                    );
            }
        ]);
    }
}
