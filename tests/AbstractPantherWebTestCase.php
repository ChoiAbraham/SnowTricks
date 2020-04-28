<?php


namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\PantherTestCase;

class AbstractPantherWebTestCase extends PantherTestCase
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
        $this->client = self::createclient();

        $this->containerService = self::$container;
        $this->entityManager = $this->containerService->get('doctrine.orm.default_entity_manager');

        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
        $schemaTool->createSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
    }
}