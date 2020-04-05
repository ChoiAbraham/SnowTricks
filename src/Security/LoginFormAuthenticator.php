<?php

namespace App\Security;

use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\Domain\Repository\UserRepository;
use App\Responders\RedirectResponder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    /** @var UserRepositoryInterface */
    private $userRepository;

    /** @var CsrfTokenManagerInterface  */
    private $csrfTokenManager;

    /** @var UserPasswordEncoderInterface  */
    private $passwordEncoder;

    /** @var RouterInterface */
    private $router;

    /** @var UrlGeneratorInterface */
    protected $urlGenerator;

    /**
     * LoginFormAuthenticator constructor.
     * @param UserRepositoryInterface $userRepository
     * @param CsrfTokenManagerInterface $csrfTokenManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param RouterInterface $router
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(UserRepositoryInterface $userRepository, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder, RouterInterface $router, UrlGeneratorInterface $urlGenerator)
    {
        $this->userRepository = $userRepository;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->router = $router;
        $this->urlGenerator = $urlGenerator;
    }

    public function supports(Request $request)
    {
        return 'security_login' === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $credentials = [
            'username' => $request->request->get('username'),
            'mail' => $request->request->get('mail'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('csrf_token'),
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['mail']
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        $user = $this->userRepository->findBy(array('name' => $credentials['username'], 'email' => $credentials['mail']));

        if (!$user) {
            throw new CustomUserMessageAuthenticationException('Mauvais identifiant');
        }

        return $user[0];
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return new RedirectResponse($this->urlGenerator->generate('homepage_action'));
    }

    protected function getLoginUrl()
    {
        return $this->router->generate('security_login');
    }
}
