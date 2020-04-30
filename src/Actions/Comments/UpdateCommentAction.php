<?php


namespace App\Actions\Comments;

use App\Actions\Interfaces\UpdateCommentActionInterface;
use App\Domain\DTO\CommentDTO;
use App\Domain\Entity\Comment;
use App\Domain\Repository\CommentRepository;
use App\Form\Handler\TrickCommentTypeHandler;
use App\Form\Type\trickCommentType;
use App\Responders\RedirectResponder;
use App\Responders\ViewResponder;
use Doctrine\ORM\EntityNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * Class EditTrickAction
 *
 * @Route("/trick/comment/edit/{id}", name="edit.comment", methods={"GET","POST"})
 * @IsGranted("ROLE_USER")
 */
final class UpdateCommentAction implements UpdateCommentActionInterface
{
//    /** @var trickRepository */
//    protected $trickRepository;
    /** @var Security */
    private $security;

    /** @var CommentRepository */
    protected $commentRepository;

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var TrickCommentTypeHandler */
    private $trickCommentTypeHandler;

    /** @var FlashBagInterface */
    private $bag;

    /**
     * UpdateCommentAction constructor.
     * @param Security $security
     * @param CommentRepository $commentRepository
     * @param FormFactoryInterface $formFactory
     * @param TrickCommentTypeHandler $trickCommentTypeHandler
     * @param FlashBagInterface $bag
     */
    public function __construct(Security $security, CommentRepository $commentRepository, FormFactoryInterface $formFactory, TrickCommentTypeHandler $trickCommentTypeHandler, FlashBagInterface $bag)
    {
        $this->security = $security;
        $this->commentRepository = $commentRepository;
        $this->formFactory = $formFactory;
        $this->trickCommentTypeHandler = $trickCommentTypeHandler;
        $this->bag = $bag;
    }


    public function __invoke(Request $request, ViewResponder $responder, RedirectResponder $redirect)
    {
        /** @var Comment $comment */
        $comment = $this->commentRepository->findOneBy(['id' => $request->attributes->get('id')]);

        // TODO - check trick is set for $comment
        // dd($comment)

        if (is_null($comment)) {
            throw new EntityNotFoundException('Pas de Commentaire');
        }

        // Hydratation DTO from Comment Entity
        /** @var CommentDTO $commentDTO */
        $commentDTO = CommentDTO::createFromEntity($comment);

        $commentForm = $this->formFactory->create(trickCommentType::class, $commentDTO)->handleRequest($request);

        $user = $this->security->getUser();
        if ($this->trickCommentTypeHandler->handleUpdateComment($commentForm, $comment) && $user != null) {
            $this->bag->add('success', 'Votre commentaire a été mise à jour');
            return $redirect('trick_action', ['slug' => $comment->getTrick()->getSlug()]);
        }

        return $responder (
            'trick/comment_page_edit.html.twig',
            [
                'form' => $commentForm->createView(),
                'comment' => $comment
            ]
        );
    }
}