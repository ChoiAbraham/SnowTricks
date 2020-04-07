<?php


namespace App\Form\Type;

use App\Domain\DTO\CreateTrickDTO;
use App\Domain\DTO\TrickDTO;
use App\Domain\Entity\GroupTrick;
use App\Domain\Entity\Trick;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateTrickType extends AbstractType implements DataMapperInterface
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
            )
            ->setDataMapper($this);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CreateTrickDTO::class,
            'empty_data' => null,
            'translation_domain' => 'forms'
        ]);
    }

    /**
     * Maps properties of some data to a list of forms.
     *
     * @param CreateTrickDTO $createTrickDTO Structured data
     * @param FormInterface[]|\Traversable $forms A list of {@link FormInterface} instances
     *
     * @throws Exception\UnexpectedTypeException if the type of the data parameter is not supported
     */
    public function mapDataToForms($createTrickDTO, $forms): void
    {
        $forms = iterator_to_array($forms);
        $forms['title']->setData($createTrickDTO ? $createTrickDTO->getTitle() : '');
        $forms['content']->setData($createTrickDTO ? $createTrickDTO->getContent() : '');
        $forms['groups']->setData($createTrickDTO ? $createTrickDTO->getGroups() : '');
        $forms['imageslinks']->setData($createTrickDTO ? $createTrickDTO->getImages() : []);
        $forms['videoslinks']->setData($createTrickDTO ? $createTrickDTO->getVideos() : []);
    }

    /**
     * Maps the data of a list of forms into the properties of some data.
     *
     * @param FormInterface[]|\Traversable $forms A list of {@link FormInterface} instances
     * @param CreateTrickDTO $createTrickDTO Structured data
     *
     * @throws Exception\UnexpectedTypeException if the type of the data parameter is not supported
     */
    public function mapFormsToData($forms, &$createTrickDTO): void
    {
        $forms = iterator_to_array($forms);
        $createTrickDTO = new CreateTrickDTO(
            $forms['title']->getData(),
            $forms['content']->getData(),
            $forms['groups']->getData(),
            $forms['imageslinks']->getData(),
            $forms['videoslinks']->getData()
        );
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