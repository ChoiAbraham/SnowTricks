<?php

namespace App\Responders;

use App\Responders\Interfaces\ViewResponderInterface;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

/**
 * Class ViewResponder.
 */
final class ViewResponder implements ViewResponderInterface
{
    /**
     * @var Environment
     */
    protected $templating;

    public function __construct(Environment $templating)
    {
        $this->templating = $templating;
    }

    public function __invoke(string $template, array $paramsTemplate = [], $redirect = false, $link = '/')
    {
        return new Response($this->templating->render($template, $paramsTemplate));
    }
}
