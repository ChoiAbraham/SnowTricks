<?php

namespace App\Form\Type;

use App\Domain\DTO\TrickVideoDTO;
use App\Domain\Entity\TrickVideo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideoType extends AbstractType implements DataMapperInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('linkvideo')
            ->add('figure', HiddenType::class)
            ->setDataMapper($this);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => TrickVideoDTO::class,
                'empty_data' => null,
                'translation_domain' => 'form_add_video'
            ]
        );
    }

    /**
     * Maps properties of some data to a list of forms.
     *
     * @param TrickVideoDTO $trickVideoDTO Structured data
     * @param FormInterface[]|\Traversable $forms A list of {@link FormInterface} instances
     *
     * @throws Exception\UnexpectedTypeException if the type of the data parameter is not supported
     */
    public function mapDataToForms($trickVideoDTO, $forms): void
    {
        $forms = iterator_to_array($forms);
        $forms['linkvideo']->setData($trickVideoDTO ? $trickVideoDTO->getPathUrl() : '');
    }

    /**
     * Maps the data of a list of forms into the properties of some data.
     *
     * @param FormInterface[]|\Traversable $forms A list of {@link FormInterface} instances
     * @param TrickVideoDTO $trickVideoDTO Structured data
     *
     * @throws Exception\UnexpectedTypeException if the type of the data parameter is not supported
     */
    public function mapFormsToData($forms, &$trickVideoDTO): void
    {
        $forms = iterator_to_array($forms);
        $trickVideoDTO = new TrickVideoDTO(
            $forms['linkvideo']->getData(),
        );
    }
}