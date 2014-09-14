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
 * Password type input field for formagic formgenerator
 *
 * @package     Formagic\Item
 * @author      Florian Sonnenburg
 * @since       0.2.0 First time introduced
 */
class Formagic_Item_Password extends Formagic_Item_Abstract
{
    /**
     * Item type
     * @var string
     */
    protected $type = 'password';

    /**
     * Returns string representation of password item.
     *
     * Will not render clear text password to HTML
     *
     * @return string
     */
    public function getHtml()
    {
        $val = $this->getValue();

        if ($this->_isReadonly) {
            $str = '<span id="' . $this->getAttribute('id') . '">'
                . str_repeat('*', strlen($val)) . '</span>';
            // $str .= '<input type="hidden" name="' . $this->_name . '" value="' . $val . '" />';
        } else {
            $attributes = $this->getAttributes();
            if(array_key_exists('value', $attributes))
            {
                unset($attributes['value']);
            }
            $attributes['type'] = 'password';
            $str = '<input' . $this->_buildAttributeStr($attributes) . ' />';
        }
        return $str;
    }

}