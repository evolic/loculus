<?php

namespace Loculus\Test\PHPUnit\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Zend\ServiceManager\ServiceManager;
use PHPUnit_Framework_TestCase;

class ModelTestCase extends PHPUnit_Framework_TestCase
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

    /**
     * Album model
     * @var Album\Model\Song
     */
    protected $model;


    public function tearDown()
    {
        unset($this->sm);
        unset($this->em);

        parent::tearDown();
    }
}