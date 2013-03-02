<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Loculus\Form\View\Helper;

use Zend\Form\ElementInterface,
    Zend\Form\View\Helper\AbstractHelper;

class FormElementClass extends AbstractHelper
{
    const CLASS_NO_ERRORS = '';
    const CLASS_HAS_ERRORS = 'input-error';

    /**
     * Render CSS class for the provided $element
     *
     * @param  ElementInterface $element
     * @param  array $attributes
     * @throws Exception\DomainException
     * @return string
     */
    public function render(ElementInterface $element, array $attributes = array())
    {
        $messages = $element->getMessages();
        if (empty($messages)) {
            return self::CLASS_NO_ERRORS;
        }

        return ' ' . self::CLASS_HAS_ERRORS;
    }

    /**
     * Invoke helper as functor
     *
     * Proxies to {@link render()} if an element is passed.
     *
     * @param  ElementInterface $element
     * @param  array $attributes
     * @return string|FormElementErrors
     */
    public function __invoke(ElementInterface $element = null, array $attributes = array())
    {
        if (!$element) {
            return $this;
        }

        return $this->render($element, $attributes);
    }
}
