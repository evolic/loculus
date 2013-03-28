<?php
namespace Loculus\Test\PHPUnit\Controller;

use Zend\I18n\Translator\Translator;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class HttpControllerTestCase extends AbstractHttpControllerTestCase
{
    /**
     * Instance of Zend Translator
     * @var Zend\I18n\Translator\Translator
     */
    protected $translator;

    public function translate($text)
    {
        if ($this->translator === null) {
            $this->translator = $this->getApplicationServiceLocator()->get('translator');
        }

        return $this->translator->translate($text);
    }

    public function translatePlural($singular, $plural, $number, $textDomain, $locale)
    {
        if ($this->translator === null) {
            $this->translator = $this->getApplicationServiceLocator()->get('translator');
        }

        return $this->translator->translatePlural($singular, $plural, $number, $textDomain, $locale);
    }
}