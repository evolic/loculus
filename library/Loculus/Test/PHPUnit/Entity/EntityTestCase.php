<?php

namespace Loculus\Test\PHPUnit\Entity;

use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceManager;
use PHPUnit_Framework_TestCase;

class EntityTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * Service Manager
     * @var Zend\ServiceManager\ServiceManager
     */
    protected $sm;

    /**
     * Doctrine Entity Manager
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;


    public function tearDown()
    {
        unset($this->sm);
        unset($this->em);

        parent::tearDown();
    }
}