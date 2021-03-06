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
 * Input type radio for formagic formgenerator
 *
 * @package     Formagic\Item
 * @author      Florian Sonnenburg
 * @since       0.2.0 First time introduced
 */
class Formagic_Item_Radio extends Formagic_Item_Abstract implements Formagic_Item_MultipleOptionsInterface
{
    /**
     * Item type
     * @var string
     */
    protected $type = 'radio';

    /**
     * Array containing radio options.
     * @var array
     */
    protected $_data = array();

    /**
     * String to separate radio elements from each other.
     * @var string
     */
    private $_separator = "\n";

    /**
     * Contains empty element.
     * @var mixed
     */
    private $_emptyElement;

    /**
     * Tells where the empty element is going.
     * @var integer
     **/
    private $_emptyPosition;

    /**
     * Place empty element as first item.
     * @constant integer
     **/
    const EMPTY_PREPEND = 1;

    /**
     * Place empty element as last item.
     * @constant integer
     **/
    const EMPTY_APPEND  = 2;

    /**
     * Radio item initialization.
     *
     * @param array $additionalArgs Array of additional options for radio item
     *
     * @throws Formagic_Exception if invalid argument is provided
     *
     * @return boolean
     */
    protected function _init($additionalArgs)
    {
        foreach ($additionalArgs as $key => $arg) {
            switch ($key){
                case 'separator':
                    $this->_separator = $arg;
                    break;
                case 'prependEmpty':
                    $this->setEmpty($arg);
                    break;
                case 'appendEmpty':
                    $this->setEmpty($arg, Formagic_Item_Radio::EMPTY_APPEND);
                    break;
                case 'data':
                    $this->setData($arg);
                    break;
                default:
                    throw new Formagic_Exception("Argument type '$key' not supported");
            } // switch
        }
    }

    /**
     * Prepends or appends empty radio input.
     *
     * @param boolean|string|array $element Label of empty element if string. 
     *      If not given, '---' is label of empty element.
     * @param integer $position Where to add empty element. Allowed values:
     *          Formagic_Item_Radio::EMPTY_APPEND
     *          Formagic_Item_Radio::EMPTY_PREPEND
     * @throws Formagic_Exception if position is invalid
     * @return Formagic_Item_Radio $this object
     */
    public function setEmpty($element = true, $position = Formagic_Item_Radio::EMPTY_PREPEND)
    {
        if (!is_string($element) && !is_array($element) && ($element !== true)) {
            throw new Formagic_Exception('Empty radio element can only be '
                . 'TRUE, string or array');
        }
        
        if (
            !($position == Formagic_Item_Radio::EMPTY_APPEND
            || $position == Formagic_Item_Radio::EMPTY_PREPEND)
        ) {
            throw new Formagic_Exception('Wrong position for empty radio '
                . 'element ("' . $position . '" was given)');
        }
        
        // element is TRUE
        if (is_bool($element)) {
            $this->_emptyElement = array('' => '---');
        
        // element is pre-defined key => value - pair
        } elseif(is_array($element)) {
            $this->_emptyElement = $element;
        
        // element value is given, but not the key
        } else {
            $this->_emptyElement = array('' => $element);
        }

        $this->_emptyPosition = $position;
        return $this;
    }

    /**
     * Sets "options" for radio elements.
     *
     * @param array $data Associative array with its key being value and
     *          its value being the label of radio elements.
     * @return $this Method chaining
     */
    public function setData(array $data)
    {
        $this->_data = $data;
        return $this;
    }

    /**
     * Returns item options array.
     *
     * @return array
     */
    public function getData()
    {
        $mergedData = $this->_data;

        // insert empty element into array of elements
        if (!is_null($this->_emptyElement)) {
            $emptyItem = is_array($this->_emptyElement)
                ? $this->_emptyElement
                : array('' => $this->_emptyElement);
            $mergedData = $this->_emptyPosition == Formagic_Item_Radio::EMPTY_APPEND
                ? $mergedData + $emptyItem
                : $emptyItem + $mergedData;
        }

        return $mergedData;
    }

    /**
     * Sets the separator string to be displayed between radio fields.
     *
     * @param string $separator Separator string
     * @return Formagic_Item_Radio Fluent interface
     */
    public function setSeparator($separator)
    {
        $this->_separator = $separator;
        return $this;
    }

    /**
     * Returns string with HTML representation of radio elements
     *
     * @return string
     */
    public function getHtml()
    {
        $currVal = htmlspecialchars($this->getValue());
        $str = '<span id="' . $this->getAttribute('id') . '">';

        $inputWithLabel = array();
        $data = $this->getData();

        // HTML blocked
        if ($this->_isReadonly) {
            $str .= '<input type="hidden" name="' . $this->getAttribute('name')
                    . '" value="' . $currVal . '" />';
            foreach ($data as $key => $value) {
                $value = htmlspecialchars($value);
                $key = htmlspecialchars($key);
                $checkIndicator = $key == $currVal ? "(o)" : "(&nbsp;&nbsp;)";
                $str .= $checkIndicator . " " . $value . $this->_separator;
            }

        // HTML default
        } else {
            $attributes = $this->getAttributes();
            $attributes['type'] = 'radio';

            $inputs = $this->getOptionInputs();
            $labels = array_values($data);
            foreach ($inputs as $key => $input) {
                $i = $key + 1;
                $inputWithLabel[] = $input
                    . '<label for="' . $attributes['id'] . $i . '">'
                    . $labels[$key]
                    . '</label>';
            }
        }

        $str .= implode($this->_separator, $inputWithLabel);
        $str .= '</span>';

        return $str;
    }

    /**
     * @deprecated since 1.5.5. Please use Formagic_Item_Radio::getOptionInputs() instead.
     */
    public function getRadioInputs()
    {
        return $this->getOptionInputs();
    }

    /**
     * Returns array of radio input strings
     *
     * @return array Radio inputs.
     */
    public function getOptionInputs()
    {
        $data = $this->getData();
        $inputs = array();

        if (!empty($data)) {
            $currVal = htmlspecialchars($this->getValue());

            $attributes = $this->getAttributes();
            $attributes['type'] = 'radio';

            $i = 0;
            foreach($data as $key => $label) {
                $i++;
                $inputValue = htmlspecialchars($key);

                $currentAttributes = $attributes;
                $currentAttributes['id'] = $attributes['id'] . $i;
                $currentAttributes['value'] = $key;

                $currentAttributes['checked'] = (string)$inputValue == (string)$currVal
                    ? 'checked'
                    : null;
                $attrStr = $this->_buildAttributeStr($currentAttributes);

                $inputs[] = '<input' . $attrStr . ' />';
            }
        }

        return $inputs;
    }

    /**
     * Returns array of radio labels.
     *
     * @deprecated since 1.5.5. Please use keys of data array instead (see {@link getData()}).
     * @return array Radio labels
     */
    public function getRadioLabels()
    {
        $data = $this->getData();
        $labels = array_values($data);

        return $labels;
    }
}
