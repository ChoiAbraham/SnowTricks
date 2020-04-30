<?php


namespace App\Domain\Builder;


use App\Domain\Builder\Interfaces\CommentBuilderInterface;
use App\Domain\DTO\CommentDTO;
use App\Domain\Entity\Comment;
use App\Domain\Entity\Trick;
use App\Domain\Entity\User;
use Symfony\Component\Security\Core\Security;

class CommentBuilder implements CommentBuilderInterface
{
    /**
     * @var Comment
     */
    private $comment;

    /** @var Security */
    private $security;

    /**
     * CommentBuilder constructor.
     * @param Comment $comment
     * @param Security $security
     */
    public function __construct(Comment $comment, Security $security)
    {
        $this->comment = $comment;
        $this->security = $security;
    }

    /**
     * @return CommentBuilder
     */
    public function create(CommentDTO $dto): self
    {
        $user = $this->security->getUser();
        $this->comment = new Comment(
            $dto->getText(),
            $user
        );

        return $this;
    }

    /**
     * @return Comment
     */
    public function getComment(): Comment
    {
        return $this->comment;
    }
}