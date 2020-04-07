<?php

namespace App\Form\Type;

use App\Domain\DTO\TrickImageDTO;
use App\Domain\Entity\TrickImage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ImageType extends AbstractType implements DataMapperInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id',
                HiddenType::class,
                [
                    'required' => false,
                    'mapped' => false,
                ]
            )
            ->add(
                'image',
                FileType::class,
                [
                    'data_class' => null,
                    'label' => 'Votre image',
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
            )
            ->setDataMapper($this);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TrickImageDTO::class,
            'empty_data' => null,
        ]);
    }

    /**
     * Maps properties of some data to a list of forms.
     *
     * @param TrickImageDTO $trickImageDTO Structured data
     * @param FormInterface[]|\Traversable $forms A list of {@link FormInterface} instances
     *
     * @throws Exception\UnexpectedTypeException if the type of the data parameter is not supported

     */
    public function mapDataToForms($trickImageDTO, $forms): void
    {
        $forms = iterator_to_array($forms);
        $forms['id']->setData($trickImageDTO ? $trickImageDTO->getId() : null);
        $forms['alt']->setData($trickImageDTO ? $trickImageDTO->getAlt() : '');
        $forms['image']->setData($trickImageDTO ? $trickImageDTO->getImage() : '');
        $forms['first_image']->setData($trickImageDTO ? $trickImageDTO->getFirstimage() : false);
    }

    /**
     * Maps the data of a list of forms into the properties of some data.
     *
     * @param FormInterface[]|\Traversable $forms A list of {@link FormInterface} instances
     * @param TrickImageDTO $trickImageDTO Structured data
     *
     * @throws Exception\UnexpectedTypeException if the type of the data parameter is not supported
     */
    public function mapFormsToData($forms, &$trickImageDTO): void
    {
        $forms = iterator_to_array($forms);
        $trickImageDTO = new TrickImageDTO(
            $forms['id']->getData(),
            $forms['alt']->getData(),
            $forms['image']->getData(),
            $forms['first_image']->getData(),
        );
    }
}
