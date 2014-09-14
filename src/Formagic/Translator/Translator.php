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
 * @author      Florian Sonnenburg
 * @copyright   2007-2014 Florian Sonnenburg
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
 * @package     Formagic\Translator
 * @author      Florian Sonnenburg
 * @since       1.0.0 First time introduced
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
     *
     * @param string $string The string to be translated
     * @param array $arguments Array of arguments to be inserted into the string
     *
     * @return string The translated string
     */
    public function _($string, array $arguments = array())
    {
        return $this->translate($string, $arguments);
    }

    /**
     * {@inheritDoc}
     *
     * @param string $string The string to be translated
     * @param array $arguments Array of arguments to be inserted into the string
     *
     * @return string The translated string
     */
    public function translate($string, array $arguments = array())
    {
        if (!$this->_callback) {
            return $string;
        }
        $res = call_user_func($this->_callback, $string, $arguments);
        return $res;
    }
}
