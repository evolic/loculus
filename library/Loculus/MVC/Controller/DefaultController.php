<?php
/**
 * Extended controller class
 *
 * @copyright Copyright (c) 2013 Tomasz Kuter <evolic_at_interia_dot_pl>
 * @license   http://evolic.eu5.org/en/new-bsd-licence.html New BSD License
 */

namespace Loculus\Mvc\Controller;

use Zend\Cache\Storage\StorageInterface,
    Zend\Mvc\Controller\AbstractActionController,
    Zend\Mvc\Controller\ActionController,
    Zend\Mvc\MvcEvent,
    Zend\EventManager\Event,
    Zend\EventManager\StaticEventManager,
    Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager;
use Loculus\Log;

/**
 * Extended controller class
 * @author Tomasz Kuter <evolic_at_interia_dot_pl>
 *
 */
class DefaultController extends AbstractActionController
{
    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * Cache adapter
     * @var Zend\Cache\Storage\StorageInterface
     */
    protected $cacheAdapter;

    /**
     * View model
     * @var Zend\View\Model\ViewModel
     */
    protected $viewModel;


    public function __construct()
    {
        $event = $this->getEvent();
        $this->viewModel = $event->getViewModel();

        $sharedEvents = StaticEventManager::getInstance();
        $sharedEvents->attach('Zend\Mvc\Controller\AbstractActionController', MvcEvent::EVENT_DISPATCH, function(Event $event) {
            // assign flashmessanger messages
            $messages = $this->flashmessenger()->getSuccessMessages();
            $viewModel->setVariable('messages', $messages);
            $info = $this->flashmessenger()->getInfoMessages();
            $viewModel->setVariable('info', $info);
            $warnings = $this->flashmessenger()->getMessages();
            $viewModel->setVariable('warnings', $warnings);
            $errors = $this->flashmessenger()->getErrorMessages();
            $viewModel->setVariable('errors', $errors);
        });
    }

    /**
     * Execute the request
     *
     * @param  MvcEvent $e
     * @return mixed
     */
    public function onDispatch(MvcEvent $e)
    {
        parent::onDispatch($e);
        $this->getServiceLocator()->get('Zend\Log')->info('onDispatch');
    }


    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getEntityManager()
    {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }
        return $this->em;
    }

    public function setCacheAdapter(StorageInterface $cacheAdapter)
    {
        $this->cacheAdapter = $cacheAdapter;
    }

    public function getCacheAdapter()
    {
        if (null === $this->cacheAdapter) {
            $this->cacheAdapter = $this->getServiceLocator()->get('Zend\Cache\Storage\Filesystem');
        }
        return $this->cacheAdapter;
    }

    public function translate($text)
    {
        $helper = $this->getServiceLocator()->get('viewhelpermanager')->get('translate');
        return $helper($text);
    }
}