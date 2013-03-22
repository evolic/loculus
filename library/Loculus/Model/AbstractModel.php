<?php
/**
 * Base model class
 *
 * @copyright Copyright (c) 2013 Tomasz Kuter <evolic_at_interia_dot_pl>
 * @license   http://evolic.eu5.org/en/new-bsd-licence.html New BSD License
 */

namespace Loculus\Model;


use Doctrine\ORM\EntityManager;
use Zend\Cache\Storage\StorageInterface,
    Zend\ServiceManager\ServiceManager;

/**
 * Base model class
 * @author Tomasz Kuter <evolic_at_interia_dot_pl>
 *
 */
class AbstractModel
{
    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var Zend\ServiceManager\ServiceManager
     */
    protected $serviceLocator;

    /**
     * Instance of Zend\Cache\Storage\StorageInterface
     * @var Zend\Cache\Storage\StorageInterface
     */
    protected $cache;


    public function __construct(EntityManager $em, StorageInterface $cache = null)
    {
        $this->firephp = \FirePHP::getInstance(true);
        $this->setEntityManager($em);

        if (isset($cache) && $cache instanceof StorageInterface) {
            $this->setCache($cache);
        }
    }


    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getEntityManager()
    {
        return $this->em;
    }

    public function setServiceLocator(ServiceManager $sl)
    {
        $this->serviceLocator = $sl;
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function setCache(StorageInterface $cache)
    {
        $this->cache = $cache;
    }

    public function getCache()
    {
        return $this->cache;
    }
}