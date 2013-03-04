<?php

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
        $this->viewModel = new ViewModel();

        $this->viewModel->setVariable('construct', __NAMESPACE__ . '\\' . __CLASS__);

//         if (!$this->getServiceLocator()->get('AuthService')->hasIdentity()) {
//             $identity = $this->getServiceLocator()->get('AuthService')->getIdentity();
//         } else {
//             $identity = null;
//         }
//         $this->viewModel->setVariable('identity', $identity);


        $sharedEvents = StaticEventManager::getInstance();
        $sharedEvents->attach('Zend\Mvc\Controller\AbstractActionController', MvcEvent::EVENT_DISPATCH, function(Event $event) {
            // get view model
            $viewModel = $event->getViewModel();

            // assign identity
            $this->getServiceLocator()->get('Zend\Log')->info(
                $this->getServiceLocator()->get('AuthService')->hasIdentity() ? 'has identity' : 'has not identity'
            );

            $identity = null;
            if ($this->getServiceLocator()->get('AuthService')->hasIdentity()) {
                $identity = $this->getServiceLocator()->get('AuthService')->getIdentity();
            }
            $this->getServiceLocator()->get('Zend\Log')->info(is_array($identity) ? 'is array' : 'is not an array');
//             $this->getServiceLocator()->get('Zend\Log')->info(new Log\Converter($identity));
            $viewModel->setVariable('identity', $identity);

//             $this->flashmessenger()->addMessage('Sample warning');
//             $this->flashmessenger()->addSuccessMessage('Sample success message');
//             $this->flashmessenger()->addErrorMessage('Sample error');

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