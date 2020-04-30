<?php

namespace App\Responders;

use App\Responders\Interfaces\RedirectResponderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RedirectResponder implements RedirectResponderInterface
{
    /** @var UrlGeneratorInterface */
    protected $urlGenerator;

    /**
     * RedirectResponder constructor.
     */
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function __invoke(string $routeName, array $params = [])
    {
        return new RedirectResponse($this->urlGenerator->generate($routeName, $params));
    }
}
