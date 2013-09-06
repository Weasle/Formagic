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
 * @package     Rule
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2007-2011 Florian Sonnenburg
 * @license     http://www.formagic-php.net/license-agreement/   New BSD License
 */

/**
 * Abstract rule class
 */
require_once 'Abstract.php';

/**
 * Abstract item class
 */
Formagic::loadClass('Formagic_Item_Abstract');

/**
 * Checks if validated value matches a specified counterpart.
 *
 * @category    Formagic
 * @package     Rule
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2007-2011 Florian Sonnenburg
 * @license     http://www.formagic-php.net/license-agreement/   New BSD License
 * @version     $Id: Equal.php 173 2012-05-16 13:19:22Z meweasle $
 **/
class Formagic_Rule_Equal extends Formagic_Rule_Abstract
{
    /**
     * Default error message
     * @var string
     **/
    protected $_message = 'Values do not match';

    /**
     * Item to be compared to
     * @var Formagic_Item_Abstract
     */
    protected $_comparedItem;

    /**
     * Allow subclass initialization.
     *
     * Supported keys:
     * <dl>
     * <dt>item</dt>: <dd>The item which's value is compaired against</dd>
     * </dl>
     *
     * @param array $additionalArgs Array of options
     * @throws Formagic_Exception If key "item" is not provided or has invalid
     * value
     * @return void
     */
    protected function _init(array $additionalArgs)
    {
        if (
            !array_key_exists('item', $additionalArgs)
            || !($additionalArgs['item'] instanceOf Formagic_Item_Abstract)
        ) {
            throw new Formagic_Exception(
                'Equal rule requires Formagic item to compare to'
            );
        }
        $this->_comparedItem = $additionalArgs['item'];
    }

    /**
     * Performs rule check
     *
     * @param string $value Value to be checked
     * @return boolean True if the two values are identical
     **/
    public function validate($value)
    {
        $compVal = $this->_comparedItem->getValue();

        if ((string)$value !== (string)$compVal) {
            return false;
        }
        return true;
    }
}
