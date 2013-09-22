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
 * @package     Rule
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2007-2009 Florian Sonnenburg
 * @license     http://www.formagic-php.net/license-agreement/   New BSD License
 */

/**
 * FormagicRule abstract superclass
 *
 * @category    Formagic
 * @package     Rule
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2007-2009 Florian Sonnenburg
 * @version     $Id: Abstract.php 173 2012-05-16 13:19:22Z meweasle $
 **/
abstract class Formagic_Rule_Abstract
{
    /**
     * Message string
     * @var string
     */
    protected $_message = 'Please enter a valid value';

    /**
     * Rule type identifier.
     * @var string
     */
    private $_name;

    /**
     * Constructor
     *
     * The default error message that is displayed if the rule is violated can
     * be overwritten by $errorMessage.
     *
     * @param array $arguments The rule options.
     * @see self::_init()
     **/
    public function __construct(array $arguments=array())
    {
        if (!empty($arguments['message'])) {
            $this->setMessage($arguments['message']);
            unset($arguments['message']);
        }
        $this->_init($arguments);
    }

    /**
     * Allows subclass initialization.
     *
     * @param array $arguments Array of arguments passed to __construct()
     * @return void
     */
    protected function _init(array $arguments)
    {
    }

    /**
     * Sets the error message property.
     *
     * Usually a rule will have one error message string that is used if the
     * validation fails. If neccessary, Formagic_Rule_Abstract::_errorMessage
     * can hold other variables, eg. an array of error messages for different
     * outcomes of the validation process.
     *
     * Implements a fluent interface pattern.
     *
     * @param mixed $message The error message value.
     * @see Formagic_Rule_Abstract::__construct()
     * @return Formagic_Rule_Abstract $this object.
     */
    public function setMessage($message)
    {
        $this->_message = $message;
        return $this;
    }

    /**
     * Returns translated error message of rule
     *
     * @return string Message string
     */
    public function getMessage()
    {
        $translate = Formagic::getTranslator();
        $message = $translate->_($this->_message);
        return $message;
    }

    /**
     * Returns rule type identification string.
     *
     * @return string The rule name
     */
    public function getName()
    {
        if (!$this->_name) {
            $this->_name = str_replace('Formagic_Rule_', '', get_class($this));
        }
        return $this->_name;
    }

    /**
     * Checks if value is not entered by a user or if the field has not been
     * assigned to the form.
     *
     * The value 0 (zero) for example could be something the user entered
     * willingly and would be skipped from rule checks when using empty().
     *
     * @param $value mixed Value to be checked
     * @return boolean
     */
    protected function _isNoUserValue($value)
    {
        return (('' === $value) || (null === $value));
    }

    /**
     * Abstract of main validate method. Returns boolean value of check success.
     *
     * Subclasses have to implement their behavior here.
     *
     * @param string $value The value to be validated.
     * @return boolean The rule check result
     **/
    abstract public function validate($value);

}
