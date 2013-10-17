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
 * @copyright   Copyright (c) 2007-2013 Florian Sonnenburg
 * @license     http://www.formagic-php.net/license-agreement/   New BSD License
 */

/**
 * Formagic Rule interface
 *
 * @category    Formagic
 * @package     Rule
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2013 Florian Sonnenburg
 **/
interface Formagic_Rule_Interface
{
    /**
     * Sets the error message property.
     *
     * @param string $message The error message value.
     * @return Formagic_Rule_Interface Fluent interface
     */
    public function setMessage($message);

    /**
     * Returns translated error message of rule
     *
     * @return string Message string
     */
    public function getMessage();

    /**
     * Returns rule type identification string.
     *
     * @return string The rule name
     */
    public function getName();

    /**
     * Main validate method.
     *
     * Returns boolean value of check success.
     *
     * @param string $value The value to be validated.
     * @return boolean The rule check result
     **/
    public function validate($value);

}
