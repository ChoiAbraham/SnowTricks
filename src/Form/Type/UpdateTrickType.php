<?php


namespace App\Form\Type;

use App\Domain\DTO\UpdateTrickDTO;
use App\Domain\Entity\Trick;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateTrickType extends AbstractType
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
                TextareaType::class
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
                    // each entry in the array will be an "FileType" field
                    'entry_type' => ImageType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
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
            'data_class' => UpdateTrickDTO::class,
            'empty_data' => function(FormInterface $form) {
                return UpdateTrickDTO(
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