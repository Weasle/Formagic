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
 * Checks if value is between min and max length
 *
 * If only one numeric value is passed as argument, this value is interpreted
 * as min length value.
 *
 * Supported arguments are:
 *  - (integer)min: Minimum length of submitted value
 *  - (integer)max: Maximum length of submitted value
 *
 * Usage examples:
 * <code>
 * // at least 10 characters
 * $item->addRule('NumericRange', array('min' => 10));
 *
 * // effectively the same as addRule('mandatory')
 * $item->addRule('NumericRange', array('min' => 1));
 *
 * // 0 to 10 characters
 * $item->addRule('NumericRange', array('max' => 10));
 *
 * // 5 to 10 characters
 * $item->addRule('NumericRange', array('min' => 5, 'max' => 10));
 * </code>
 *
 * @package     Formagic\Rule
 * @author      Marc Schrader
 * @author      Florian Sonnenburg
 * @since       1.0.0 First time introduced
 **/
class Formagic_Rule_NumericRange extends Formagic_Rule_RangeComparison_Abstract
{
    /**
     * Default error messages
     * @var array
     **/
    protected $_messages = array(
        'min'     => 'Please enter a value higher than %s',
        'max'     => 'Please enter a value lower than %s',
        'between' => 'Please enter a value between %s and %s'
    );

    /**
     * Returns value as integer.
     * 
     * @param string $value Item value
     * @return integer Item value as integer
     */
    protected function _getRange($value)
    {
        return (int)$value;
    }
}
