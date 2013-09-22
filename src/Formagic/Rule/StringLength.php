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
 * @author      Marc Schrader
 * @copyright   Copyright (c) 2007-2011 Florian Sonnenburg
 * @license     http://www.formagic-php.net/license-agreement/   New BSD License
 */

/**
 * Checks if value is between min and max length
 *
 * If only one numeric value is passed as argument, this value is interpreted
 * as min length value.
 *
 * Supported arguments are:
 * <code>
 * Formagic_Rule_StringLength::__construct(array(
 *      'min' => (int)$min, 
 *      'max' => (int)$max,
 *      'messages' => array(
 *          'min' => (string)$errorMessage,
 *          'min' => (string)$errorMessage,
 *          'between' => (string)$errorMessage,     
 *      )
 * ));
 * Formagic_Rule_StringLength::__construct(array('min' => (int)$min, 'max' => (int)$max));
 * </code>
 *
 * @category    Formagic
 * @package     Rule
 * @subpackage  RangeComparsion
 * @author      Florian Sonnenburg
 * @author      Marc Schrader
 * @copyright   Copyright (c) 2007-2011 Florian Sonnenburg
 * @version     $Id: StringLength.php 173 2012-05-16 13:19:22Z meweasle $
 **/
class Formagic_Rule_StringLength extends Formagic_Rule_RangeComparison_Abstract
{
    /**
     * Default error messages
     * @var array
     **/
    protected $_messages = array(
        'min' => 'Please enter at least %s characters',
        'max' => 'Please enter no more than %s characters',
        'between' => 'Please enter between %s and %s characters'
    );

    /**
     * Returns range check value.
     * 
     * @param string $value Item value
     * @return integer Range check value
     */
    protected function _getRange($value)
    {
        $length = strlen($value);
        return $length;
    }
}
