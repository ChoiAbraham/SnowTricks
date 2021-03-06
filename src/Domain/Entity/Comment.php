<?php

namespace App\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Comment.
 *
 * @ORM\Table(name="comment_entity")
 * @ORM\Entity(repositoryClass="App\Domain\Repository\CommentRepository")
 */
class Comment
{
    public const NUMBER_PER_PAGE = 4;

    /**
     * @var id
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    protected $content;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\Entity\Trick", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $trick;

    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\Entity\User", inversedBy="comments")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    /**
     * Comment constructor.
     *
     * @param DateTime $createdAt
     */
    public function __construct(string $text = '', User $user = null)
    {
        $this->content = $text;
        $this->user = $user;
        $this->createdAt = new \DateTime();
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    public function getTrick(): ?Trick
    {
        return $this->trick;
    }

    public function setTrick(?Trick $trick): self
    {
        $this->trick = $trick;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
