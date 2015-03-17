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
    private $enableMultiByte = false;

    /**
     * @var string
     */
    private $charsetEncoding;

    /**
     * @var array
     */
    private $multiByteEnginesRanking;

    /**
     * @const string
     */
    const MULTIBYTE_ENGINE_MBSTRING = 'mb_strlen';

    /**
     * @const string
     */
    const MULTIBYTE_ENGINE_ICONV = 'iconv_strlen';

    /**
     * Subclass initialization.
     *
     * @param array $arguments Supported arguments:
     *  - enableMultiByte: Enables multiByte support. Default: disabled
     *  - charsetEncoding: Encoding for multiByte functions. Default: System default
     *  - multiByteEngineRanking: Order in which given multiByte functions are tested and executed. Default:
     *      - mb_strlen
     *      - iconv_strlen
     */
    protected function _init(array $arguments)
    {
        $this->multiByteEnginesRanking = array(
            self::MULTIBYTE_ENGINE_MBSTRING,
            self::MULTIBYTE_ENGINE_ICONV
        );

        if (!empty($arguments['enableMultiByte'])) {
            $this->setEnableMultiByte($arguments['enableMultiByte']);
        }
        if (!empty($arguments['charsetEncoding'])) {
            $this->setCharsetEncoding($arguments['charsetEncoding']);
        }
        if (!empty($arguments['multiByteEngineRanking'])) {
            $this->setMultiByteEngineRanking($arguments['multiByteEngineRanking']);
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
     * @throws Formagic_Exception if enableMultiByte and no multibyte extension is not installed
     * @return integer Range check value
     */
    protected function _getRange($value)
    {
        if ($this->enableMultiByte) {
            $length = null;
            foreach ($this->multiByteEnginesRanking as $engine) {
                // skip engine if not installed
                if (!function_exists($engine)) {
                    continue;
                }

                switch ($engine) {
                    case self::MULTIBYTE_ENGINE_MBSTRING:
                        if ($this->charsetEncoding) {
                            $length = mb_strlen($value, $this->charsetEncoding);
                        } else {
                            $length = mb_strlen($value);
                        }
                        break 2;
                    case self::MULTIBYTE_ENGINE_ICONV:
                        if ($this->charsetEncoding) {
                            $length = iconv_strlen($value, $this->charsetEncoding);
                        } else {
                            $length = iconv_strlen($value);
                        }
                        break 2;
                }
            }
            if (null === $length) {
                throw new Formagic_Exception('No multiByte function defined or none installed');
            }

        } else {
            $length = strlen($value);
        }

        return $length;
    }

    /**
     * Enables or disables multibyte support.
     *
     * @param boolean $enableMultiByte New multibyte support status
     * @return $this Method chaining
     */
    public function setEnableMultiByte($enableMultiByte)
    {
        $this->enableMultiByte = $enableMultiByte;
        return $this;
    }

    /**
     * Defines which multiByte engine is selected first.
     *
     * Two multibyte functions are prepared with the constants Formagic_Rule_StringLength::MULTIBYTE_ENGINE_MBSTRING
     * and Formagic_Rule_StringLength::MULTIBYTE_ENGINE_ICONV.
     *
     * @param array $multiByteEngineRanking
     * @return $this Method chaining
     */
    public function setMultiByteEngineRanking(array $multiByteEngineRanking)
    {
        $this->multiByteEnginesRanking = $multiByteEngineRanking;
        return $this;
    }

    /**
     * Sets charset encoding used for multibyte functions.
     *
     * If not provided, the
     *
     * @param $charsetEncoding
     * @return $this Method chaining
     */
    public function setCharsetEncoding($charsetEncoding)
    {
        $this->charsetEncoding = $charsetEncoding;
        return $this;
    }
}
