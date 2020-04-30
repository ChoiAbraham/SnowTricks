<?php


namespace App\Form\Type;

use App\Domain\DTO\CommentDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class trickCommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text', TextareaType::class,
                [
                    'label' => 'Ajouter un commentaire'
                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => CommentDTO::class,
                'empty_data' => function (FormInterface $form) {
                    return new CommentDTO(
                        $form->get('text')->getData()
                    );
                },
            ]
        );
    }
}