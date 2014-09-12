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
 * Formagic_Item_Input
 *
 * @package     Formagic\Item
 * @author      Florian Sonnenburg
 * @since       0.1.0 First time introduced
 */
class Formagic_Item_Input extends Formagic_Item_Abstract
{
    /**
     * @var string
     */
    protected $type = 'text';

    /**
     * HTML string representation of text input field
     *
     * @return string
     */
    public function getHtml()
    {
        $val = htmlspecialchars($this->getValue());

        if ($this->_isReadonly) {
            $attributes = $this->getAttributes();
            $str = $val;
            $str .= '<input type="hidden" id="' . $attributes['id'] . '" name="'
                . $attributes['name'] . '" value="' . $val . '" />';
        } else {
            $attributes = $this->getAttributes();
            $attributes['type']  = 'text';
            $attributes['value'] = $val;
            $str = '<input' . $this->_buildAttributeStr($attributes) . ' />';
        }
        return $str;
    }

}