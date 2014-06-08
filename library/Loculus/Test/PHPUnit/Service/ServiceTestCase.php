<?php

namespace Loculus\Test\PHPUnit\Form;

use Zend\Form\Form;
use Zend\ServiceManager\ServiceManager;
use PHPUnit_Framework_TestCase;

class ServiceTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * Service Manager
     * @var Zend\ServiceManager\ServiceManager
     */
    protected $sm;

    protected $service;


    public function tearDown()
    {
        unset($this->sm);
        unset($this->service);

        parent::tearDown();
    }
}
