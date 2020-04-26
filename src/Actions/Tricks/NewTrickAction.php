<?php

namespace App\Actions\Tricks;

use App\Actions\Interfaces\NewTrickActionInterface;
use App\Domain\DTO\TrickDTO;
use App\Domain\Repository\TrickRepository;
use App\Form\Handler\Interfaces\AddTrickTypeHandlerInterface;
use App\Form\Type\CreateTrickType;
use App\Responders\Interfaces\ViewResponderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class NewTrickAction
 *
 * @Route("/trick/new", name="new_trick")
 */
final class NewTrickAction implements NewTrickActionInterface
{
    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var TrickRepository */
    protected $trickRepository;

    /** @var AddTrickTypeHandlerInterface */
    private $addTrickTypeHandler;

    /**
     * HomepageAction constructor.
     * @param FormFactoryInterface $formFactory
     * @param TrickRepository $trickRepository
     * @param AddTrickTypeHandlerInterface $addTrickTypeHandler
     */
    public function __construct(FormFactoryInterface $formFactory, TrickRepository $trickRepository, AddTrickTypeHandlerInterface $addTrickTypeHandler)
    {
        $this->formFactory = $formFactory;
        $this->trickRepository = $trickRepository;
        $this->addTrickTypeHandler = $addTrickTypeHandler;
    }

    public function __invoke(Request $request, ViewResponderInterface $responder)
    {
        $addTrickType = $this->formFactory->create(CreateTrickType::class)->handleRequest($request);

        //if no user => redirect to no user

        if ($this->addTrickTypeHandler->handle($addTrickType)) {

            // ... Redirect??
        }

        return $responder (
            'trick/trick_form.html.twig',
            [
                'form' => $addTrickType->createView()
            ]
        );
    }
}
