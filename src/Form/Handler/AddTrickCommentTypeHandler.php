<?php


namespace App\Form\Handler;


use App\Domain\Builder\Interfaces\CommentBuilderInterface;
use App\Domain\DTO\CreateCommentDTO;
use App\Domain\Entity\Comment;
use App\Domain\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

class AddTrickCommentTypeHandler
{
    /**
     * @var CommentBuilderInterface
     */
    private $commentBuilder;

    /**
     * @var CommentRepository
     */
    private $commentRepository;

    /** @var EntityManagerInterface */
    private $em;

    /**
     * AddTrickCommentTypeHandler constructor.
     * @param CommentBuilderInterface $commentBuilder
     * @param CommentRepository $commentRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(CommentBuilderInterface $commentBuilder, CommentRepository $commentRepository, EntityManagerInterface $em)
    {
        $this->commentBuilder = $commentBuilder;
        $this->commentRepository = $commentRepository;
        $this->em = $em;
    }

    public function handle(FormInterface $form): bool
    {
        if ($form->isSubmitted() && $form->isValid()) {

            // TODO - Add Comment Functionality

//            /** @var CreateCommentDTO $data */
//            $data = $form->getData();
//            /** @var Comment $comment */
//            $comment = $this->commentBuilder->create($form->getData())->getComment();

//            $this->em->persist($trick);
//            $this->em->flush();

            return true;
        }

        return false;
    }
}