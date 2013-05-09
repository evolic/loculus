<?php

namespace Loculus\Test\PHPUnit\Form;

use Zend\Form\Form;
use Zend\ServiceManager\ServiceManager;
use PHPUnit_Framework_TestCase;

class FormTestCase extends PHPUnit_Framework_TestCase
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
     * Entity instance
     * @var Loculus\Entity
     */
    protected $entity;

    /**
     * Form instance
     * @var Zend\Form\Form
     */
    protected $form;


    public function tearDown()
    {
        unset($this->sm);
        unset($this->em);
        unset($this->entity);
        unset($this->form);

        parent::tearDown();
    }
}