<?php


namespace App\Form\Handler;

use App\Domain\Builder\Interfaces\CommentBuilderInterface;
use App\Domain\DTO\CommentDTO;
use App\Domain\Entity\Comment;
use App\Domain\Entity\Trick;
use App\Domain\Entity\User;
use App\Domain\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

class TrickCommentTypeHandler
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
     * TrickCommentTypeHandler constructor.
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

    public function handleAddComment(FormInterface $form, Trick $trick): bool
    {
        if ($form->isSubmitted() && $form->isValid()) {

            /** @var CommentDTO $data */
            $data = $form->getData();
            /** @var Comment $comment */
            $comment = $this->commentBuilder->create($data)->getComment();
            $trick->comments($comment);

            $this->em->persist($comment);
            $this->em->flush();

            return true;
        }

        return false;
    }

    public function handleUpdateComment(FormInterface $form, Comment $comment): bool
    {
        if ($form->isSubmitted() && $form->isValid()) {

            /** @var CommentDTO $data */
            $data = $form->getData();

            $comment->setUpdatedAt(new \DateTime());
            $comment->setContent($data->getText());

            $this->em->flush();

            return true;
        }

        return false;
    }
}