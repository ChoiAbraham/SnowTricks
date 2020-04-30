<?php

namespace App\DataFixtures\Tests;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

abstract class BaseTestFixture extends Fixture implements FixtureGroupInterface
{
    /** @var ObjectManager */
    private $manager;

    /** @var Generator */
    protected $faker;

    private $referencesIndex = [];

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->faker = Factory::create('fr_FR');
        $this->loadData($manager);
    }

    protected function createMany(string $className, int $count, callable $factory)
    {
        for ($i = 0; $i < $count; $i++) {
            $entity = new $className();
            $factory($entity, $i);

            $this->manager->persist($entity);
            // store for usage later as App\Entity\ClassName_#COUNT#
            $this->addReference($className . '_' . $i, $entity);
        }
    }

    protected function getRandomReference(string $className) {
        if (!isset($this->referencesIndex[$className])) {
            $this->referencesIndex[$className] = [];

            foreach ($this->referenceRepository->getReferences() as $key => $ref) {
                if (strpos($key, $className.'_') === 0) {
                    $this->referencesIndex[$className][] = $key;
                }
            }
        }

        if (empty($this->referencesIndex[$className])) {
            throw new \Exception(sprintf('Cannot find any references for class "%s"', $className));
        }

        $randomReferenceKey = $this->faker->randomElement($this->referencesIndex[$className]);

        return $this->getReference($randomReferenceKey);
    }

    abstract protected function loadData(ObjectManager $manager);

    public static function getGroups(): array
    {
        return ['test'];
    }
}