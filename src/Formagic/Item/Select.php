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
 * formagicItemSelect
 *
 * @package     Formagic\Item
 * @author      Florian Sonnenburg
 * @since       0.2.0 First time introduced
 */
class Formagic_Item_Select extends Formagic_Item_Abstract
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
     * Returns the HTML string for a multiselectbox.
     *
     * @return string The HTML string.
     */
    protected function _getMultipleSelect()
    {
        $currVal    = $this->getValue();
        $attributes = $this->getAttributes();

        // Multi HTML readonly
        if($this->_isReadonly) {
            $t = Formagic::getTranslator();
            if (!is_array($currVal)) {
                $currVal = array($currVal);
            }
            $labels  = array();
            $hiddens = array();
            foreach ($currVal as $val) {
                $val = htmlspecialchars($val);
                $labels[]  = $t->_($this->_data[$val]);
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
        $str = '<input type="hidden" name="' . $this->getAttribute('name')
            . '" value="" />'
            . '<select' . $this->_buildAttributeStr($attributes) . ">\n";
        $str .= $this->_buildOptions($this->_data, $currVal);
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
            $t = Formagic::getTranslator();
            $str = '<span id="' . $this->getAttribute('id') . '">['
                . $t->_($this->_data[$currVal]) . ']<input '
                . 'type="hidden" name="' . $this->getAttribute('name')
                . '" value="' . $currVal . '" /></span>';
            return $str;
        }

        // Single HTML default
        $str = '<select' . $this->getAttributeStr() .">\n";
        $str .= $this->_buildOptions($this->_data, $currVal);
        $str .= '</select>';
        return $str;
    }

    /**
     * Build select options string.
     *
     * @param array $data Select options
     * @param string $currentVal Current value
     * @return string Options string
     */
    protected function _buildOptions(array $data, $currentVal)
    {
        $t = Formagic::getTranslator();
        $str = '';
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $label = htmlspecialchars($t->_($key));
                $str .= "<optgroup label=\"" . $label . "\">\n";
                $str .= $this->_buildOptions($value, $currentVal);
                $str .= "</optgroup>\n";
            } else {
                $key = htmlspecialchars($key);
                $value = htmlspecialchars($t->_($value));
                if (is_array($currentVal)) {
                    $selected = in_array((string)$key, $currentVal) ? ' selected="selected"' : '';
                } else {
                    $selected = (string)$key == (string)$currentVal ? ' selected="selected"' : '';
                }
                $str .= "\t<option value=\"$key\"$selected>$value</option>\n";
            }
        }
        return $str;
    }
}
