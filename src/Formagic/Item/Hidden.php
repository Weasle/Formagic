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
 * @copyright   Copyright (c) 2007-2009 Florian Sonnenburg
 * @license     http://www.formagic-php.net/license-agreement/   New BSD License
 */

/**
 * Formagic_Item_Hidden
 *
 * @category    Formagic
 * @package     Item
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2013 Florian Sonnenburg
 */
class Formagic_Item_Hidden extends Formagic_Item_Abstract
{
    /**
     * @const Item type
     */
    const ITEM_TYPE = 'hidden';

    /**
     * Object initialisation.
     *
     * @param array $additionalArgs Ignored for this item
     * @return void
     */
    protected function _init($additionalArgs)
    {
        $this->_isHidden = true;
        $this->_isReadonly = true;
    }

    /**
     * Returns hidden field HTML
     *
     * @return string HTML output
     */
    public function  getHtml() {
        $attributes = $this->getAttributes();
        $attributes['value'] = htmlspecialchars($this->getValue());
        $attributes['type'] = 'hidden';
        $attributesStr = $this->_buildAttributeStr($attributes);

        $html = '<input ' . $attributesStr . ' />';
        return $html;
    }
}
