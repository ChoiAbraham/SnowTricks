<?php

namespace App\Domain\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Trick.
 *
 * @ORM\Table(name="trick_entity")
 * @ORM\Entity(repositoryClass="App\Domain\Repository\TrickRepository")
 */
class Trick
{
    public const LIST_GROUPS = [
        'Stances',
        'Straight airs',
        'Grabs',
        'Les rotations',
        'Flips',
        'Les rotations désaxées',
        'Slides',
        'Les one foot tricks',
        'Old School',
        'Autres'
    ];
    public const NUMBER_OF_TRICKS_IN_HOMEPAGE = 6;

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
     * @ORM\Column(type="string", length=255)
     */
    protected $title;

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
    protected $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
    protected $slug;

    /**
     * @ORM\OneToMany(targetEntity="App\Domain\Entity\Comment", mappedBy="trick")
     */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\Entity\User")
     * @ORM\JoinColumn(nullable=true)
     */
    private $creator;

    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\Entity\User")
     * @ORM\JoinColumn(nullable=true)
     */
    private $lastUser;

    /**
     * @ORM\OneToMany(targetEntity="App\Domain\Entity\TrickVideo", mappedBy="trick", cascade={"persist"})
     */
    private $trickVideos;

    /**
     * @ORM\OneToMany(targetEntity="App\Domain\Entity\TrickImage", mappedBy="trick", cascade={"persist"})
     */
    private $trickImages;

    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\Entity\GroupTrick", inversedBy="trick")
     */
    private $groupTrick;

    /**
     * Trick constructor.
     */
    public function __construct($title = '', $content = '', $group = null, User $user = null)
    {
        $this->createdAt = new \DateTime();
        $this->comments = new ArrayCollection();
        $this->trickVideos = new ArrayCollection();
        $this->trickImages = new ArrayCollection();
        $this->title = $title;
        $this->content = $content;
        $this->groupTrick = $group;
        $this->setSlug();
        $this->setCreator($user);
        $this->setLastUser($user);
    }

    public function comments(Comment $comment)
    {
        $comment->setTrick($this);
        $this->addComment($comment);
    }

    public function images(TrickImage $trickImage)
    {
        $trickImage->setTrick($this);
        $this->addTrickImage($trickImage);
    }

    public function videos(TrickVideo $trickVideo)
    {
        $trickVideo->setTrick($this);
        $this->addTrickVideo($trickVideo);
    }

    public function setTrickImages(array $trickImages): self
    {
        $this->trickImages->clear();
        foreach ($trickImages as $image) {
            $this->trickImages->add($image);
        }

        return $this;
    }

    public function setTrickVideos(array $trickVideos): self
    {
        $this->trickVideos->clear();
        foreach ($trickVideos as $video) {
            $this->trickVideos->add($video);
        }

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(): void
    {
        $slugify = new Slugify();
        $this->slug = $slugify->slugify($this->getTitle());
    }

    /**
     * @return id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setTrick($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getTrick() === $this) {
                $comment->setTrick(null);
            }
        }

        return $this;
    }

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    public function getLastUser(): ?User
    {
        return $this->lastUser;
    }

    public function setLastUser(?User $lastUser): self
    {
        $this->lastUser = $lastUser;

        return $this;
    }

    /**
     * @return Collection|TrickVideo[]
     */
    public function getTrickVideos(): Collection
    {
        return $this->trickVideos;
    }

    public function addTrickVideo(TrickVideo $trickVideo): self
    {
        if (!$this->trickVideos->contains($trickVideo)) {
            $this->trickVideos[] = $trickVideo;
            $trickVideo->setTrick($this);
        }

        return $this;
    }

    public function removeTrickVideo(TrickVideo $trickVideo): self
    {
        if ($this->trickVideos->contains($trickVideo)) {
            $this->trickVideos->removeElement($trickVideo);
            // set the owning side to null (unless already changed)
            if ($trickVideo->getTrick() === $this) {
                $trickVideo->setTrick(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TrickImage[]
     */
    public function getTrickImages()
    {
        return $this->trickImages;
    }

    public function addTrickImage(TrickImage $trickImage): self
    {
        if (!$this->trickImages->contains($trickImage)) {
            $this->trickImages[] = $trickImage;
            $trickImage->setTrick($this);
        }

        return $this;
    }

    public function removeTrickImage(TrickImage $trickImage): self
    {
        if ($this->trickImages->contains($trickImage)) {
            $this->trickImages->removeElement($trickImage);
            // set the owning side to null (unless already changed)
            if ($trickImage->getTrick() === $this) {
                $trickImage->setTrick(null);
            }
        }

        return $this;
    }

    public function getGroupTrick()
    {
        return $this->groupTrick;
    }

    public function setGroupTrick($groupTrick)
    {
        $this->groupTrick = $groupTrick;
    }
}
