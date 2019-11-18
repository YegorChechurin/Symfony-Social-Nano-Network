<?php

namespace App\Tests;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

// https://www.thinktocode.com/2018/06/14/symfony-unit-testing-with-a-database/

class FixtureAwareTestCase extends KernelTestCase
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Kernel
     */
    private $kernel;

    /**
     * @var ORMExecutor
     */
    private $fixtureExecutor;

    /**
     * @var ContainerAwareLoader
     */
    private $fixtureLoader;

    protected function setUp()
    {
        $this->kernel = self::bootKernel();

        $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    /**
     * Adds a new fixture to be loaded.
     */
    protected function addFixture(FixtureInterface $fixture)
    {
        $this->getFixtureLoader()->addFixture($fixture);
    }

    /**
     * Executes all the fixtures that have been loaded so far.
     */
    protected function executeFixtures()
    {
        $this->getFixtureExecutor()->execute($this->getFixtureLoader()->getFixtures());
    }

    /**
     * Get the class responsible for loading the data fixtures.
     * And this will also load in the ORM Purger which purges the database before loading in the data fixtures
     */
    private function getFixtureExecutor(): ORMExecutor
    {
        if (!$this->fixtureExecutor) {
            $this->fixtureExecutor = new ORMExecutor($this->em, new ORMPurger($this->em));
        }

        return $this->fixtureExecutor;
    }

    /**
     * Get the Doctrine data fixtures loader
     */
    private function getFixtureLoader(): ContainerAwareLoader
    {
        if (!$this->fixtureLoader) {
            $this->fixtureLoader = new ContainerAwareLoader($this->kernel->getContainer());
        }

        return $this->fixtureLoader;
    }
}