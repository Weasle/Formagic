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
 * Email rule checks if a valid email address is entered.
 *
 * The following arguments are supported:
 *  - (boolean)'checkDns': Performs DNS lookup
 *
 * @package     Formagic\Rule
 * @author      Marc Schrader
 * @author      Florian Sonnenburg
 * @since       0.2.0 First time introduced
 **/
class Formagic_Rule_Email extends Formagic_Rule_Abstract
{
    /**
     * Default error message
     * @var string
     **/
    protected $_message = 'Please enter a valid mail address';

    /**
     * Validation strategy
     * @var Formagic_Rule_EmailValidation_Interface
     */
    private $_validationStrategy;

    /**
     * Defines if reverse lookup of mail domain DNS is executed
     * @var boolean
     */
    private $_checkDns = false;

    /**
     * Initializes email rule.
     * Supported keys:
     * <dl>
     * <dt>checkDns:</dt><dd>Boolean flag to decide if a DNS lookup for the
     * given mail domain is performed with the validation</dd>
     * <dt>validationStrategy:</dt><dd>Implementation of
     * {@link Formagic_Rule_EmailValidation_Interface} that decides if a mail
     * address is valid.</dd>
     * </dl>
     *
     * @param array $arguments Array of arguments
     * @throws Formagic_Exception If given validationStrategy is not valid
     */
    protected function _init(array $arguments)
    {
        if (!empty($arguments['checkDns'])) {
            $this->_checkDns = $arguments['checkDns'];
        }

        if (array_key_exists('validationStrategy', $arguments)) {
            if (!($arguments['validationStrategy'] instanceOf
                    Formagic_Rule_EmailValidation_Interface)
            ) {
                throw new Formagic_Exception('Invalid validation strategy');
            }
            $this->_validationStrategy = $arguments['validationStrategy'];
        }
    }

    /**
     * Returns validation provider for the item.
     *
     * Defaults to Formagic_Rule_EmailValidation_PhpFilter if PHP version
     * suffices, or to more expensive Formagic_Rule_EmailValidation_Regex if not.
     *
     * @return Formagic_Rule_EmailValidation_Interface
     */
    private function _getValidationStrategy()
    {
        if (is_null($this->_validationStrategy)) {
            $strategy = 'Regex';
            if(function_exists('filter_var')) {
                $strategy = 'PhpFilter';
            }
            $className = 'Formagic_Rule_EmailValidation_' . $strategy;
            $this->_validationStrategy = new $className();
        }
        return $this->_validationStrategy;
    }

    /**
     * Performs rule check
     *
     * @param string $value Item value object to be validated
     * @return boolean True if the item value is email formatted
     **/
    public function validate($value)
    {
        // assume test passed if no value is set
        if ($this->_isNoUserValue($value)) {
            return true;
        }

        // Check value with Regex
        if (!$this->_getValidationStrategy()->isValidEmailAddress($value)) {
            return false;
        }

        // Check if DNS lookup is supported by PHP version and requested
        // by user
        if (!$this->_checkDns) {
            return true;
        }

        // Check DNS
        $tokens = explode('@', $value);
        if (!checkdnsrr($tokens[1], 'MX') && !checkdnsrr($tokens[1], 'A')) {
            return false;
        }

        return true;
    }
}