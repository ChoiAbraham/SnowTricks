<?php

namespace App\Tests\Actions;

use App\Actions\HomepageAction;
use App\Actions\Interfaces\HomeActionInterface;
use App\Domain\Repository\TrickRepository;
use App\Responders\ViewResponder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class HomepageActionTest extends TestCase
{
    /** @var TrickRepository */
    private $trickRepository;

    /** @var Environment */
    private $twigEnvironment;

    public function setUp()
    {
        $this->trickRepository = $this->createMock(TrickRepository::class);
        $this->twigEnvironment = $this->createMock(Environment::class);
    }

    public function testConstruct()
    {
        $homepageAction = new HomepageAction(
            $this->trickRepository
        );
        static::assertInstanceOf(HomeActionInterface::class, $homepageAction);
    }

    public function testReturnAResponse()
    {
        $request = Request::create(
            '/',
            'GET'
        );
        $responder = new ViewResponder(
            $this->twigEnvironment
        );

        $homepageAction = new HomepageAction(
            $this->trickRepository
        );

        static::assertInstanceOf(
            Response::class,
            $homepageAction($request, $responder)
        );
    }
}