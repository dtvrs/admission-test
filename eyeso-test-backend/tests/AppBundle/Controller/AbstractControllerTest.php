<?php

namespace Tests\AppBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractControllerTest extends WebTestCase
{
    protected static $application;


    protected function setUp()
    {
        static::runCommand('doctrine:database:drop --force');
        static::runCommand('doctrine:database:create');
        static::runCommand('doctrine:schema:create');
        static::runCommand('doctrine:fixtures:load --append --no-interaction');

        $this->client        = static::createClient();
        $this->container     = $this->client->getContainer();
        $this->entityManager = $this->container->get('doctrine.orm.entity_manager');

        parent::setUp();
    }

    protected function tearDown()
    {
        static::runCommand('doctrine:database:drop --force');

        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
    }
    
    protected static function runCommand($command)
    {
        $command = sprintf('%s --quiet', $command);

        return static::getApplication()->run(new StringInput($command));
    }
    
    protected static function getApplication()
    {
        if (null === static::$application) {
            $client = static::createClient();

            static::$application = new Application($client->getKernel());
            static::$application->setAutoExit(false);
        }

        return static::$application;
    }
}
