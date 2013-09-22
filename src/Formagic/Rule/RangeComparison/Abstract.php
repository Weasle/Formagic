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
 * @subpackage  RangeComparsion
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2007-2011 Florian Sonnenburg
 * @license     http://www.formagic-php.net/license-agreement/   New BSD License
 */

/**
 * Abstract implementation of range comparsion rules
 *
 * @category    Formagic
 * @package     Rule
 * @subpackage  RangeComparsion
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2007-2011 Florian Sonnenburg
 * @version     $Id: Abstract.php 173 2012-05-16 13:19:22Z meweasle $
 **/
abstract class Formagic_Rule_RangeComparison_Abstract extends Formagic_Rule_Abstract
{
    /**
     * The minimal length
     * @var integer
     */
    protected $_min;

    /**
     * The maximal length
     * @var integer
     */
    protected $_max;

    /**
     * Array of messages
     * @var array
     */
    protected $_messages = array(
        'min'     => '',
        'max'     => '',
        'between' => ''
    );

    /**
     * Allows subclass initialization.
     *
     * Allowed options for this rule are:
     * <dl>
     *  <dt>(integer)min:</dt><dd>Minimal length to be checked (optional, defaults to 0)</dd>
     *  <dt>(integer)max:</dt><dd>Maximal length to be checked (optional, defaults to 0)</dd>
     * </dl>
     *
     * Will throw an exception, if neither "min" nor "max" option provided.
     *
     * @param array $arguments Array of arguments passed to __construct()
     * @return void
     */
    protected function _init(array $arguments)
    {
        if (isset($arguments['min']) && is_numeric($arguments['min'])) {
            $this->_min = (int)$arguments['min'];
        }
        if (isset($arguments['max']) && is_numeric($arguments['max'])) {
            $this->_max = (int)$arguments['max'];
        }
        if ($this->_min === null && $this->_max === null) {
            throw new Formagic_Exception("Comparsion rule needs 'min' and/or 'max' argument");
        }
        if (isset($arguments['messages'])) {
            $this->setMessages($arguments['messages']);
        }
    }

    /**
     * Not supported by range comparison rules.
     *
     * @param string $message New message string
     * @throws Formagic_Exception
     */
    public function setMessage($message) {
        throw new Formagic_Exception('Single message not supported. Please use'
            . __CLASS__ . '::setMassages() instead.');
    }

    /**
     * Sets messages array
     *
     * @param array $messages Array of messages
     * @return Formagic_Rule_RangeComparison_Abstract
     */
    public function setMessages(array $messages)
    {
        $diff = array_diff_key($this->_messages, $messages);
        if (!empty($diff)) {
            throw new Formagic_Exception('Error message has to be array with '
                . 'keys "min", "max" and "between"');
        }
        $this->_messages = $messages;
        return $this;
    }

    /**
     * Returns range check value.
     *
     * @param string $value Item value
     * @return mixed Check range value
     */
    abstract protected function _getRange($value);

    /**
     * Performs rule check
     *
     * Length rule checks if value is between min and max length. The validation
     * method depends on $this->_type.
     *
     * @param string $value The item to checked
     * @return boolean The validation result
     **/
    public function validate($value)
    {
        // assume it is valid not to enter a value
        if ($this->_isNoUserValue($value)) {
            return true;
        }

        $range = $this->_getRange($value);

        // Check min and max length
        if ($this->_min !== null && $this->_max !== null) {
            return $this->_execBetween($range);

        // only max length given
        } elseif($this->_max !== null) {
            return $this->_execMax($range);

        // only min length given
        } else {
            return $this->_execMin($range);
        }
    }

    /**
     * Evaluate maximum range (lessers or equals).
     *
     * @param integer $range Upper limit
     * @return boolean Evaluation result
     */
    protected function _execMax($range)
    {
        if ($range <= $this->_max) {
            return true;
        } else {
            $msg = $this->_getMessage('max');
            $this->_message = sprintf($msg, $this->_max);
            return false;
        }
    }

    /**
     * Evaluate minimum range (greater or equals).
     *
     * @param integer $range Lower limit
     * @return boolean Evaluation result
     */
    protected function _execMin($range)
    {
        if ($range >= $this->_min) {
            return true;
        } else {
            $msg = $this->_getMessage('min');
            $this->_message = sprintf($msg, $this->_min);
            return false;
        }
    }

    /**
     * Evaluate between range (including limits).
     *
     * @param integer $range Range value
     * @return boolean Evaluation result
     */
    protected function _execBetween($range)
    {
        if ($range >= $this->_min && $range <= $this->_max) {
            return true;
        } else {
            $msg = $this->_getMessage('between');
            $this->_message = sprintf($msg, $this->_min, $this->_max);
            return false;
        }
    }

    /**
     * Returns a translates message string.
     *
     * @param string $key Message type
     * @return string Translated filled message
     */
    protected function _getMessage($key)
    {
        $message = $this->_messages[$key];
        $msg = Formagic::getTranslator()->_($message);
        return $msg;
    }

}
