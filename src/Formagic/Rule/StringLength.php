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
 * @package     Formagic\Rule
 * @author      Marc Schrader
 * @author      Florian Sonnenburg
 * @since       1.0.0 First time introduced
 **/
class Formagic_Rule_StringLength extends Formagic_Rule_RangeComparison_Abstract
{
    /**
     * @var boolean
     */
    private $multiByteSupportEnabled = false;

    /**
     * @var string
     */
    private $charsetEncoding;

    /**
     * @var string
     */
    private $forceMultiByteEngine;

    /**
     * @const string
     */
    const MULTIBYTE_ENGINE_MBSTRING = 'mb_strlen';

    /**
     * @const string
     */
    const MULTIBYTE_ENGINE_ICONV = 'iconv_strlen';

    /**
     * @param array $arguments
     * @throws Formagic_Exception
     */
    protected function _init(array $arguments)
    {
        if (!empty($arguments['multiByteSupportEnabled'])) {
            $this->multiByteSupportEnabled = $arguments['multiByteSupportEnabled'];
        }
        if (!empty($arguments['charsetEncoding'])) {
            $this->charsetEncoding = $arguments['charsetEncoding'];
        }
        if (!empty($arguments['forceMultiByteEngine'])) {
            $this->forceMultiByteEngine = $arguments['forceMultiByteEngine'];
        }
        parent::_init($arguments);
    }

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
     * @throws Formagic_Exception if multiByteSupportEnabled and no multibyte extension is not installed
     * @return int Range check value
     */
    protected function _getRange($value)
    {
        if ($this->multiByteSupportEnabled) {
            if ($this->hasMultiByteEngine(self::MULTIBYTE_ENGINE_MBSTRING)) {
                $length = mb_strlen($value, $this->charsetEncoding);
            } elseif($this->hasMultiByteEngine(self::MULTIBYTE_ENGINE_ICONV)) {
                $length = iconv_strlen($value, $this->charsetEncoding);
            } else {
                throw new Formagic_Exception('Neither "mbstring" nor "iconv" extension is installed');
            }
        } else {
            $length = strlen($value);
        }

        return $length;
    }

    private function hasMultiByteEngine($engine)
    {
        $engineExists = function_exists($this->forceMultiByteEngine);
        $engineForced = ($this->forceMultiByteEngine == $engine);
        if (!$engineExists && $engineForced) {
            throw new Formagic_Exception('Forced multibyte engine ' . $this->forceMultiByteEngine . ' is not installed');
        }

        return $engineExists;
    }
}
