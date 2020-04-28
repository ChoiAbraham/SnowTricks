<?php

namespace App\Actions\Tricks;

use App\Actions\Interfaces\DeleteTrickActionInterface;
use App\Domain\Entity\Trick;
use App\Domain\Entity\TrickImage;
use App\Domain\Repository\CommentRepository;
use App\Domain\Repository\TrickImageRepository;
use App\Domain\Repository\TrickRepository;
use App\Domain\Repository\TrickVideoRepository;
use App\Responders\Interfaces\ViewResponderInterface;
use App\Responders\RedirectResponder;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/**
 * Class DeleteTrickAction
 *
 * @Route("/delete/{slug}", name="edit_trick_delete")
 * @IsGranted("ROLE_USER")
 */
final class DeleteTrickAction extends AbstractController implements DeleteTrickActionInterface
{
    /** @var EntityManagerInterface */
    private $manager;

    /**
     * @var TokenStorageInterface *
     */
    private $tokenStorage;

    /** @var TrickRepository */
    private $trickRepository;

    /** @var TrickImageRepository */
    private $trickImageRepository;

    /** @var TrickVideoRepository */
    private $trickVideoRepository;

    /** @var CommentRepository */
    private $commentRepository;

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /** @var string */
    private $uploadPath;

    /**
     * @var CsrfTokenManagerInterface
     */
    private $csrf;

    /** @var Filesystem */
    private $filesystem;

    /**
     * DeleteTrickAction constructor.
     * @param EntityManagerInterface $manager
     * @param TokenStorageInterface $tokenStorage
     * @param TrickRepository $trickRepository
     * @param TrickImageRepository $trickImageRepository
     * @param TrickVideoRepository $trickVideoRepository
     * @param CommentRepository $commentRepository
     * @param FlashBagInterface $flashBag
     * @param string $uploadPath
     * @param CsrfTokenManagerInterface $csrf
     * @param Filesystem $filesystem
     */
    public function __construct(EntityManagerInterface $manager, TokenStorageInterface $tokenStorage, TrickRepository $trickRepository, TrickImageRepository $trickImageRepository, TrickVideoRepository $trickVideoRepository, CommentRepository $commentRepository, FlashBagInterface $flashBag, string $uploadPath, CsrfTokenManagerInterface $csrf, Filesystem $filesystem)
    {
        $this->manager = $manager;
        $this->tokenStorage = $tokenStorage;
        $this->trickRepository = $trickRepository;
        $this->trickImageRepository = $trickImageRepository;
        $this->trickVideoRepository = $trickVideoRepository;
        $this->commentRepository = $commentRepository;
        $this->flashBag = $flashBag;
        $this->uploadPath = $uploadPath;
        $this->csrf = $csrf;
        $this->filesystem = $filesystem;
    }

    public function __invoke(Request $request, ViewResponderInterface $responder, RedirectResponder $redirect)
    {
        /** @var Trick $trickEntity */
        $trickEntity = $this->trickRepository->findOneBy(['slug' => $request->attributes->get('slug')]);

        if ($this->csrf->isTokenValid(new CsrfToken('delete' . $trickEntity->getId(), $request->get('_token')))) {
            if($this->tokenStorage->getToken()->getUser()) {
                $comments = $this->commentRepository->findBy(['trick' => $trickEntity->getId()]);
                foreach ($comments as $comment) {
                    $this->manager->remove($comment);
                }

                $images = $this->trickImageRepository->findBy(['trick' => $trickEntity->getId()]);
                foreach ($images as $image) {
                    /** @var TrickImage $image */
                    $this->filesystem->remove(
                        [
                            '',
                            '',
                            $this->uploadPath . '/trick_picture/' . $image->getImageFileName(),
                        ]
                    );
                    $this->manager->remove($image);
                }
                $videos = $this->trickVideoRepository->findBy(['trick' => $trickEntity->getId()]);
                foreach ($videos as $video) {
                    $this->manager->remove($video);
                }

                $this->manager->remove($trickEntity);
                $this->manager->flush();

                $this->flashBag->add('success', 'La figure a été supprimé');
                return $redirect('homepage_action');
            }
        }

        return $redirect('homepage_action');
    }
}
