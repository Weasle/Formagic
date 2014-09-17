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
 * Formagic submit button item
 *
 * @package     Formagic\Item
 * @author      Florian Sonnenburg
 * @since       0.2.0 First time introduced
 */
class Formagic_Item_Submit extends Formagic_Item_Abstract
{
    /**
     * Item type
     * @var string
     */
    protected $type = 'submit';

   /**
     * Subclass initialization
     *
     * @param array $additionalArgs Ignored for this item
     * @return void
     */
    protected function _init($additionalArgs)
    {
        // can't be edited
        $this->_isReadonly = true;
    }

    /**
     * HTML string representation of submit button
     *
     * @return string HTML string
     */
    public function getHtml()
    {
        $label = Formagic::getTranslator()->_($this->_label);
        $attributes = $this->getAttributes();
        $attributes['type'] = 'submit';
        $attributes['value'] = $label;
        $str = '<input' . $this->_buildAttributeStr($attributes) . ' />';
        return $str;
    }

    /**
     * Label is already defined by value property
     *
     * @return string Empty string
     **/
    public function getLabel()
    {
        return "";
    }

    /**
     * Returns true if submit button was triggered to access current page
     *
     * @return boolean Triggered status
     */
    public function isTriggered()
    {
        $val = $this->getValue();
        $res = isset($val) ? true : false;
        return $res;
    }
}