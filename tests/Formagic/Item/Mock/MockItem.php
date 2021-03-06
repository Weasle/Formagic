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
 * Mock item class
 *
 * @package     Formagic\Tests
 * @author      Florian Sonnenburg
 **/
class Formagic_Item_Mock_MockItem extends Formagic_Item_Abstract
{
    const HTML_OUTPUT = 'myHtmlOutput';
    const HTML_OUTPUT_READONLY = 'myHtmlOutputReadonly';

    /**
     * 
     * @return string
     */
    public function getHtml() 
    {
        if($this->_isReadonly) {
            return self::HTML_OUTPUT_READONLY;
        } else {
            return self::HTML_OUTPUT;
        }
    }
    
    /**
     *
     * @return string 
     */
    public function getParentHtml()
    {
        return parent::getHtml();
    }
}
