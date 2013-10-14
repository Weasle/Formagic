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
 * Formagic submit button item
 *
 * @category    Formagic
 * @package     Item
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2007 Florian Sonnenburg
 */
class Formagic_Item_Button extends Formagic_Item_Abstract
{
    /**
     * Sets item to readonly.
     *
     * If attribute "type" is not given, the item defaults to type "button"
     *
     * @param array $additionalArgs Array of addition arguments
     * @return void
     */
    protected function _init($additionalArgs)
    {
        // can't be edited
        $this->_isReadonly = true;

        // button type defaults to "button"
        if (empty($this->_attributes['type'])) {
            $this->_attributes['type'] = 'button';
        }
    }

    /**
     * HTML string representation of submit button
     *
     * @return string
     */
    public function getHtml()
    {
        $str = '<button' . $this->getAttributeStr() . '>'
            . $this->_label . '</button>';
        return $str;
    }

    /**
     * Label is already defined by value property
     *
     * @return string
     **/
    public function getLabel()
    {
        return "";
    }
}