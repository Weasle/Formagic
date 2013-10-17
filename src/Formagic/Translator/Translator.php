<?php
/**
 * Formagic
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at
 * http://www.formagic-php.net/license-agreement/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@formagic-php.net so we can send you a copy immediately.
 *
 * @category    Formagic
 * @package     Translator
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2007-2013 Florian Sonnenburg
 * @license     http://www.formagic-php.net/license-agreement/   New BSD License
 */

/**
 * Formagic translator class
 *
 * Provides an standard interface used by all Formagic framework classes.
 * Contains no stand-alone translation features: If no translation callback is
 * provided, Formagic_Translator will simply return the same string that was
 * passed in the first place.
 *
 * @category    Formagic
 * @package     Translator
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2013 Florian Sonnenburg
 */
class Formagic_Translator implements Formagic_Translator_Interface
{
    /**
     * Callback array containing the translation class and method
     * @var array
     */
    private $_callback;

    /**
     * Constructor
     *
     * Empty by default, allows subclass initialization.
     */
    public function __construct()
    {
    }

    /**
     * Sets the translation callback with class and method.
     *
     * @param mixed $object Translation class
     * @param string $method Translation class
     * @return void
     */
    public function setCallback($object, $method)
    {
        $this->_callback = array($object, $method);
    }

    /**
     * {@inheritDoc}
     */
    public function _($string, array $arguments = array())
    {
        if (!$this->_callback) {
            return $string;
        }
        $res = call_user_func($this->_callback, $string);
        return $res;
    }
}
