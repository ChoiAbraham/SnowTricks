<?php

namespace App\Form\Type;

use App\Domain\DTO\CreateTrickDTO;
use App\Domain\DTO\TrickDTO;
use App\Domain\Entity\Trick;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateTrickType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ) {
        $builder
            ->add(
                'title',
                TextType::class,
                [
                    'label' => 'Titre',
                    'attr' => [
                        'class' => 'form-title'
                    ],
                    'required' => false,
                ]
            )
            ->add(
                'content',
                TextareaType::class,
                [
                    'required' => false,
                ]
            )
            ->add(
                'groups',
                ChoiceType::class,
                [
                    'label' => false,
                    'choices' => $this->getChoices(),
                    'placeholder' => 'GROUPE',
                    'attr' => [
                        'class' => 'form-subject'
                    ],
                    'required' => false,
                ]
            )
            ->add(
                'imageslinks',
                CollectionType::class,
                [
                    'label' => false,
                    'entry_type' => ImageType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                ]
            )
            ->add(
                'videoslinks',
                CollectionType::class,
                [
                    'label' => false,
                    'entry_type' => VideoType::class,
                    'entry_options' => ['label' => false],
                    'allow_add' => true,
                    'allow_delete' => true,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CreateTrickDTO::class,
            'empty_data' => function (FormInterface $form) {
                return new CreateTrickDTO(
                    $form->get('title')->getData(),
                    $form->get('content')->getData(),
                    $form->get('groups')->getData(),
                    $form->get('imageslinks')->getData(),
                    $form->get('videoslinks')->getData()
                );
            },
            'translation_domain' => 'forms'
        ]);
    }

    private function getChoices()
    {
        $choices = Trick::LIST_GROUPS;
        $output = [];
        foreach($choices as $k => $v) {
            $output[$v] = $k;
        }
        return $output;
    }
}