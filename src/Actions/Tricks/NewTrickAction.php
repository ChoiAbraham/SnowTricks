<?php

namespace App\Actions\Tricks;

use App\Actions\Interfaces\NewTrickActionInterface;
use App\Domain\Repository\TrickRepository;
use App\Form\Handler\Interfaces\AddTrickTypeHandlerInterface;
use App\Form\Type\CreateTrickType;
use App\Responders\Interfaces\ViewResponderInterface;
use App\Responders\RedirectResponder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class NewTrickAction.
 *
 * @Route("/trick/new", name="new_trick")
 * @IsGranted("ROLE_USER")
 */
final class NewTrickAction implements NewTrickActionInterface
{
    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var TrickRepository */
    protected $trickRepository;

    /** @var AddTrickTypeHandlerInterface */
    private $addTrickTypeHandler;

    /** @var FlashBagInterface */
    private $bag;

    /**
     * NewTrickAction constructor.
     */
    public function __construct(FormFactoryInterface $formFactory, TrickRepository $trickRepository, AddTrickTypeHandlerInterface $addTrickTypeHandler, FlashBagInterface $bag)
    {
        $this->formFactory = $formFactory;
        $this->trickRepository = $trickRepository;
        $this->addTrickTypeHandler = $addTrickTypeHandler;
        $this->bag = $bag;
    }

    public function __invoke(Request $request, ViewResponderInterface $responder, RedirectResponder $redirect)
    {
        $addTrickType = $this->formFactory->create(CreateTrickType::class)->handleRequest($request);

        //if no user => redirect to no user

        if ($this->addTrickTypeHandler->handle($addTrickType)) {
            $this->bag->add('success', 'Votre Trick a bien été ajouté');

            return $redirect('homepage_action');
        }
        if (true == $this->addTrickTypeHandler->checkImage()) {
            $this->bag->add('success', 'Votre Trick a été ajouté sur votre compte. Pour l\'ajouter à la liste des tricks, ajouter ou sélectionner une première image');

            return $redirect('my_account');
        }

        return $responder(
            'trick/trick_form.html.twig',
            [
                'form' => $addTrickType->createView(),
            ]
        );
    }
}
