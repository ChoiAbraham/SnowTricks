<?php


namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class AbstractWebTestCase extends WebTestCase
{
    use FixturesTrait;

    /** @var KernelBrowser */
    protected $client;

    /** @var EntityManagerInterface */
    protected $entityManager;

    /** @var ContainerInterface */
    protected $containerService;

    protected function setUp()
    {
        $this->client = self::createClient();

        $this->containerService = self::$container;
        $this->entityManager = $this->containerService->get('doctrine')->getManager();

//        $this->entityManager = $this->containerService->get('doctrine.orm.default_entity_manager');

        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
        $schemaTool->createSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
    }
}