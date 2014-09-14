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
 * Formagic_Item_Hidden
 *
 * @package     Formagic\Item
 * @author      Florian Sonnenburg
 * @since       0.2.0 First time introduced
 */
class Formagic_Item_Hidden extends Formagic_Item_Abstract
{
    /**
     * Item type
     * @var string
     */
    protected $type = 'hidden';

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
