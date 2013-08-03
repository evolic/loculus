<?php
/**
 * Class gives support to application for Bad Request response code
 * BadRequestStrategy based on class Zend\Mvc\View\HttpRouteNotFoundStrategy
 *
 * Loculus extension to Zend Framework 2 (http://framework.zend.com/)
 *
 * @copyright Copyright (c) 2013 Tomasz Kuter <evolic_at_interia_dot_pl>
 * @license   http://evolic.eu5.org/en/new-bsd-licence.html New BSD License
 */

namespace Loculus\Mvc\View\Http;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Http\Response as HttpResponse;
use Zend\Mvc\Application;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\View\Model\ViewModel;

class BadRequestStrategy implements ListenerAggregateInterface
{
    /**
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = array();

    /**
     * Whether or not to display exceptions related to the 400 condition
     *
     * @var bool
     */
    protected $displayExceptions = false;

    /**
     * Whether or not to display the reason for a 400s
     *
     * @var bool
     */
    protected $displayBadRequestReason = false;

    /**
     * Template to use to report bad request conditions
     *
     * @var string
     */
    protected $badRequestTemplate = 'error';

    /**
     * The reason for a bad-request condition
     *
     * @var false|string
     */
    protected $reason = false;

    /**
     * Attach the aggregate to the specified event manager
     *
     * @param  EventManagerInterface $events
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        $firephp = \FirePHP::getInstance(true);
        $firephp->info(__METHOD__);

        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH, array($this, 'prepareBadRequestViewModel'), -90);
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'detectBadRequestError'));
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'prepareBadRequestViewModel'));
    }

    /**
     * Set value indicating whether or not to display exceptions related to a bad-request condition
     *
     * @param  bool $displayExceptions
     * @return BadRequestStrategy
     */
    public function setDisplayExceptions($displayExceptions)
    {
        $this->displayExceptions = (bool) $displayExceptions;
        return $this;
    }

    /**
     * Should we display exceptions related to a bad-request condition?
     *
     * @return bool
     */
    public function displayExceptions()
    {
        return $this->displayExceptions;
    }

    /**
     * Detach aggregate listeners from the specified event manager
     *
     * @param  EventManagerInterface $events
     * @return void
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    /**
     * Set value indicating whether or not to display the reason for a bad-request condition
     *
     * @param  bool $displayBadRequestReason
     * @return BadRequestStrategy
     */
    public function setDisplayBadRequestReason($displayBadRequestReason)
    {
        $this->displayBadRequestReason = (bool) $displayBadRequestReason;
        return $this;
    }

    /**
     * Should we display the reason for a bad-request condition?
     *
     * @return bool
     */
    public function displayBadRequestReason()
    {
        return $this->displayBadRequestReason;
    }

    /**
     * Get template for bad request conditions
     *
     * @param  string $badRequestTemplate
     * @return BadRequestStrategy
     */
    public function setBadRequestTemplate($badRequestTemplate)
    {
        $this->badRequestTemplate = (string) $badRequestTemplate;
        return $this;
    }

    /**
     * Get template for bad request conditions
     *
     * @return string
     */
    public function getBadRequestTemplate()
    {
        return $this->badRequestTemplate;
    }

    /**
     * Detect if an error is a 400 condition
     *
     * If a "controller bad request" error type is encountered, sets the response status code to 400.
     *
     * @param  MvcEvent $e
     * @return void
     */
    public function detectBadRequestError(MvcEvent $e)
    {
        return;
    }

    /**
     * Create and return a 400 view model
     *
     * @param  MvcEvent $e
     * @return void
     */
    public function prepareBadRequestViewModel(MvcEvent $e)
    {
        $firephp = \FirePHP::getInstance(true);
        $firephp->info(__METHOD__);
        $firephp->info($e->getName(), 'event name');

        $vars = $e->getResult();
        if ($vars instanceof Response) {
            // Already have a response as the result
            return;
        }

        $response = $e->getResponse();
        if ($response->getStatusCode() != 400) {
            // Only handle 404 responses
            return;
        }

        if (!$vars instanceof ViewModel) {
            $firephp->info('creating new ViewModel');
            $model = new ViewModel();
            if (is_string($vars)) {
                $model->setVariable('message', $vars);
            } else {
                $model->setVariable('message', 'Bad request.');
            }
        } else {
            $firephp->info('updating existing view model');
            $model = $vars;
            if ($model->getVariable('message') === null) {
                $model->setVariable('message', 'Bad request.');
            }
        }

        $firephp->info($model->getTemplate(), 'view model template');
        $model->setTemplate($this->getBadRequestTemplate());

        $firephp->info($model->getVariable('reason'), 'before injecting reason');

        // If displaying reasons, inject the reason
        $this->injectBadRequestReason($model, $e);

        $firephp->info($model->getVariable('reason'), 'reason');

        // If displaying exceptions, inject
        $this->injectException($model, $e);

        $firephp->info($model->getVariable('exception'), 'exception');

        // Inject controller if we're displaying either the reason or the exception
        $this->injectController($model, $e);

        $firephp->info($model->getVariable('controller'), 'controller');

        $e->setResult($model);
    }

    /**
     * Inject the bad-request reason into the model
     *
     * If $displayBadRequestReason is enabled, checks to see if $reason is set,
     * and, if so, injects it into the model. If not, it injects
     * Application::ERROR_CONTROLLER_CANNOT_DISPATCH.
     *
     * @param  ViewModel $model
     * @return void
     */
    protected function injectBadRequestReason(ViewModel $model)
    {
        if (!$this->displayBadRequestReason()) {
            return;
        }

        // bad request cause missing parameters
        if ($this->reason) {
            $model->setVariable('reason', $this->reason);
            return;
        }

        // otherwise, must be a case of the controller not being able to
        // dispatch itself.
        $model->setVariable('reason', Application::ERROR_CONTROLLER_CANNOT_DISPATCH);
    }

    /**
     * Inject the exception message into the model
     *
     * If $displayExceptions is enabled, and an exception is found in the
     * event, inject it into the model.
     *
     * @param  ViewModel $model
     * @param  MvcEvent $e
     * @return void
     */
    protected function injectException($model, $e)
    {
        if (!$this->displayExceptions()) {
            return;
        }

        $model->setVariable('display_exceptions', true);

        $exception = $e->getParam('exception', false);
        if (!$exception instanceof \Exception) {
            return;
        }

        $model->setVariable('exception', $exception);
    }

    /**
     * Inject the controller and controller class into the model
     *
     * If either $displayExceptions or $displayBadRequestReason are enabled,
     * injects the controllerClass from the MvcEvent. It checks to see if a
     * controller is present in the MvcEvent, and, if not, grabs it from
     * the route match if present; if a controller is found, it injects it into
     * the model.
     *
     * @param  ViewModel $model
     * @param  MvcEvent $e
     * @return void
     */
    protected function injectController($model, $e)
    {
        if (!$this->displayExceptions() && !$this->displayBadRequestReason()) {
            return;
        }

        $controller = $e->getController();
        if (empty($controller)) {
            $routeMatch = $e->getRouteMatch();
            if (empty($routeMatch)) {
                return;
            }

            $controller = $routeMatch->getParam('controller', false);
            if (!$controller) {
                return;
            }
        }

        $controllerClass = $e->getControllerClass();
        $model->setVariable('controller', $controller);
        $model->setVariable('controller_class', $controllerClass);
    }
}
