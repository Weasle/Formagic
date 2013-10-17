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
 * @author      Marc Schrader
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2007-2013 Florian Sonnenburg
 * @license     http://www.formagic-php.net/license-agreement/   New BSD License
 */

/**
 * Checks if value is numeric
 *
 * @category    Formagic
 * @package     Rule
 * @author      Marc Schrader
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2011 Florian Sonnenburg
 **/
class Formagic_Rule_Numeric extends Formagic_Rule_Abstract
{
    /**
     * Default error message
     * @var string
     **/
    protected $_message = 'Please enter a numeric value';

    /**
     * Performs rule check
     *
     * Numeric rule checks if item has a numeric value.
     *
     * @param string $value The item value to be validated
     * @return boolean True if the item value is numeric
     **/
    public function validate($value)
    {
        if ($this->_isNoUserValue($value)) {
            return true;
        }

        // assume that a colon is meant as decimals separator
        $value = str_replace(',', '.', $value);
        $result = is_numeric($value);
        return $result;
    }
}
