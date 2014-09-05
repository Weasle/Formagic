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
 * Displays it's value as plain text, with no input possibility.
 *
 * @category    Formagic
 * @package     Item
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2007
 */
class Formagic_Item_Html extends Formagic_Item_Abstract
{
    /**
     * @const Item type
     */
    const ITEM_TYPE = 'html';

    /**
     * Enable or disable DIV wrapper
     * @var boolean
     */
    private $_raw;

    /**
     * Sets item to fixed and ignored.
     *
     * @param array $additionalArgs Array of options. Supported keys:
     * <dl>
     * <dt>(boolen)raw:</dt>Render or omit wrapping DIV tag for HTML output<dd></dd>
     * </dl>
     * @return void
     */
    public function _init($additionalArgs)
    {
        $this->_raw = !empty($additionalArgs['raw']);
        $this->_isFixed = true;
        $this->_isIgnored = true;
    }

    /**
     * HTML string representation of text input field
     *
     * @return string HTML string
     */
    public function getHtml()
    {
        if ($this->_raw) {
            return $this->getValue();
        }
        $id = $this->getAttribute('id');
        $str = '<div id="' . $id . '">' . $this->getValue() . '</div>';
        return $str;
    }
}
