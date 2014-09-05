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
 * @package     Item
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2007-2013 Florian Sonnenburg
 * @license     http://www.formagic-php.net/license-agreement/   New BSD License
 */

/**
 * Formagic_Item_Checkbox
 *
 * @category    Formagic
 * @package     Item
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2009 Florian Sonnenburg
 */
class Formagic_Item_Checkbox extends Formagic_Item_Abstract
{
    /**
     * @const Item type
     */
    const ITEM_TYPE = 'checkbox';

    /**
     * Returns HTML representation of checkbox item.
     *
     * @see $_isReadonly
     * @return string
     */
    public function getHtml()
    {
        $checked = $this->getValue() ? 1 : 0;
        if ($this->_isReadonly) {
            $str = $checked ? "[X]" : "[_]";
            $str .= '<input type="hidden" id="' . $this->getAttribute('id')
                . '" name="' . $this->getAttribute('name')
                . '" value="' . $checked . '" />';
        } else {
            $attributes = $this->getAttributes();
            $attributes['value'] = '1';

            if (!array_key_exists('class', $attributes)) {
                $attributes['class'] = 'checkbox';
            }
            if ($checked) {
                $attributes['checked'] = 'checked';
            }
            $attrStr = $this->_buildAttributeStr($attributes);

            $str = '<input type="hidden" name="' . $this->getAttribute('name')
                . '" value="0" />';
            $str .= '<input type="checkbox"' . $attrStr . ' />';
        }
        return $str;
    }
}
