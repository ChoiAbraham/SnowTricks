<?php

namespace App\Domain\DTO;

use App\Domain\Entity\Comment;
use Symfony\Component\Validator\Constraints as Assert;

class CommentDTO
{
    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     */
    protected $text;

    /**
     * CommentDTO constructor.
     */
    public function __construct(?string $text = '')
    {
        $this->text = $text;
    }

    public static function createFromEntity(Comment $comment): self
    {
        $dto = new CommentDTO();
        $dto->setText($comment->getContent());

        return $dto;
    }

    /**
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): void
    {
        $this->text = $text;
    }
}
