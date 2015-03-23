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
 * @copyright   2007-2015 Florian Sonnenburg
 * @license     http://www.formagic-php.net/license-agreement/   New BSD License
 */

/**
 * Formagic_Item_MultipleOptionsInterface
 *
 * @package     Formagic\Item
 * @author      Florian Sonnenburg
 * @since       1.5.5 First time introduced
 */
interface Formagic_Item_MultipleOptionsInterface
{
    /**
     * Sets the item options.
     *
     * @param array $data Array of select options
     * @return $this Method chaining
     */
    public function setData(array $data);

    /**
     * Returns item options array.
     *
     * @return array
     */
    public function getData();

    /**
     * Returns rendered HTML inputs for item's options.
     *
     * @return array
     */
    public function getOptionInputs();
}
