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
 * Formagic_Item_Select
 *
 * @package     Formagic\Item
 * @author      Florian Sonnenburg
 * @since       0.2.0 First time introduced
 */
class Formagic_Item_Select extends Formagic_Item_Abstract implements Formagic_Item_MultipleOptionsInterface
{
    /**
     * Item type
     * @var string
     */
    protected $type = 'select';

    /**
     * Array containing select options
     * @var array
     */
    protected $_data = array();

    /**
     * Determines if the selectbox should be displayed as a multi selectbox
     * @var boolean
     */
    protected $_multi = false;

    /**
     * Generic argument handler
     *
     * Supported arguments:
     * <dl>
     * <dt>data:</dt><dd>Array of SELECT options</dd>
     * <dt>multiple:</dt><dd>Boolean flag defining if multiple options can be
     * selected</dd>
     * </dl>
     *
     * @param array $additionalArgs Array of additional options for this item
     * @see setData()
     * @see setMultiple()
     * @return void
     */
    protected function _init($additionalArgs)
    {
        if (isset($additionalArgs['data'])) {
            $this->setData($additionalArgs['data']);
        }

        if (isset($additionalArgs['multiple'])) {
            $this->setMultiple($additionalArgs['multiple']);
        }
    }

    /**
     * Sets the item options.
     *
     * @param array $data Array of select options
     * @return Formagic_Item_Select $this object
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
        return $this->_data;
    }


    /**
     * Sets multiple attribute for select field.
     *
     * @param boolean $flag Bool value if the field has to be multiple.
     * @return Formagic_Item_Select $this object
     */
    public function setMultiple($flag)
    {
        $this->_multi = (bool)$flag;
        return $this;
    }

    /**
     * HTML representation of Formagic select item
     *
     * @throws Formagic_Exception if no options defined
     * @return string The string representation.
     */
    public function getHtml()
    {
        if(empty($this->_data)) {
            throw new Formagic_Exception('No options defined');
        }

        if($this->_multi) {
            $str = $this->_getMultipleSelect();
        } else {
            $str = $this->_getSingleSelect();
        }
        return $str;
    }

    /**
     * Returns rendered HTML inputs for item's options.
     *
     * @return array
     */
    public function getOptionInputs()
    {
        $currentValue = $this->getValue();
        $result = $this->getOptionsInputsRecursive($this->_data, $currentValue);
        return $result;
    }

    /**
     * Recursive worker method returning nested options.
     *
     * @param array $data Options data array
     * @param string|array $currentValue Currently set data.
     *
     * @return array
     */
    private function getOptionsInputsRecursive(array $data, $currentValue)
    {
        $translator = Formagic::getTranslator();
        $result = array();
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $key = $translator->translate($key);
                $label = htmlspecialchars($key);
                $result[$label] = $this->getOptionsInputsRecursive($value, $currentValue);
            } else {
                $value = $translator->translate($value);
                $value = htmlspecialchars($value);
                $key = htmlspecialchars($key);
                if (is_array($currentValue)) {
                    $selected = in_array($key, $currentValue) ? ' selected="selected"' : '';
                } else {
                    $selected = $key == (string)$currentValue ? ' selected="selected"' : '';
                }
                $result[] = "<option value=\"{$key}\"{$selected}>{$value}</option>";
            }
        }
        return $result;
    }

    /**
     * Build select options string.
     *
     * @param array $optionsArray Select options
     * @param string $currentVal Current value
     * @return string Options string
     */
    protected function _buildOptions(array $optionsArray, $currentVal)
    {
        $str = '';
        foreach ($optionsArray as $key => $value) {
            if (is_array($value)) {
                $str .= "<optgroup label=\"" . $key . "\">\n";
                $str .= $this->_buildOptions($value, $currentVal);
                $str .= "</optgroup>\n";
            } else {
                $str .= "\t$value\n";
            }
        }
        return $str;
    }

    /**
     * Returns the HTML string for a multiselectbox.
     *
     * @return string The HTML string.
     */
    protected function _getMultipleSelect()
    {
        $currVal    = $this->getValue();
        $attributes = $this->getAttributes();

        // Multi HTML readonly
        if ($this->_isReadonly) {
            $translator = Formagic::getTranslator();
            if (!is_array($currVal)) {
                $currVal = array($currVal);
            }
            $labels  = array();
            $hiddens = array();
            foreach ($currVal as $val) {
                $val = htmlspecialchars($val);
                $labels[]  = $translator->translate($this->_data[$val]);
                $hiddens[] = '<input type="hidden" name="' . $attributes['name']
                    . '[]" value="' . $val . '" />';
            }
            $str = '<span id="' . $attributes['id'] . '">['
                . implode(', ', $labels) . ']'  . implode('', $hiddens)
                . '</span>';
            return $str;
        }

        // Multi HTML default
        $attributes             = $this->getAttributes();
        $attributes['name']     = $attributes['name'] . '[]';
        $attributes['multiple'] = 'multiple';

        // hidden field needed because empty multi select does not transfer
        $str = '<input type="hidden" name="' . $this->getAttribute('name') . '" value="" />';
        $str .= '<select' . $this->_buildAttributeStr($attributes) . ">\n";

        $optionsArray = $this->getOptionInputs();
        $str .= $this->_buildOptions($optionsArray, $currVal);
        $str .= '</select>';
        return $str;
    }

    /**
     * Returns the HTML string for a single selectbox.
     *
     * @return string The HTML string.
     */
    protected function _getSingleSelect()
    {
        $currVal = htmlspecialchars($this->getValue());

        // Single HTML readonly
        if ($this->_isReadonly) {
            $translator = Formagic::getTranslator();
            $str = '<span id="' . $this->getAttribute('id') . '">['
                . $translator->translate($this->_data[$currVal]) . ']<input '
                . 'type="hidden" name="' . $this->getAttribute('name')
                . '" value="' . $currVal . '" /></span>';
            return $str;
        }

        // Single HTML default
        $str = '<select' . $this->getAttributeStr() .">\n";
        $optionsArray = $this->getOptionInputs();
        $str .= $this->_buildOptions($optionsArray, $currVal);
        $str .= '</select>';
        return $str;
    }
}
