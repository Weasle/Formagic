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
 * @package     Generator
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2007-2012 Florian Sonnenburg
 * @license     http://www.formagic-php.net/license-agreement/   New BSD License
 */
namespace Formagic\Generator\Properties;

use Formagic\Generator\Properties\ItemProperties;

/**
 * Formagic generator item bag
 *
 * @category    Formagic
 * @package     Generator
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2007-2012 Florian Sonnenburg
 * @version     $Id: ItemBag.php 181 2012-11-02 20:31:51Z meweasle $
 **/
class ItemBag implements \IteratorAggregate
{
    /**
     * List of filters
     * @var array
     */
    protected $_items;

    /**
     * Adds a new item to the items list.
     *
     * @param ItemProperties $item
     * @return ItemBag Fluent interface
     */
    public function addItem(ItemProperties $item)
    {
        $itemName = $item->getName();
        $this->_items[$itemName] = $item;
        return $this;
    }

    /**
     * Sets new item list.
     *
     * @param array $items Array of items
     * @return ItemBag Fluent interface
     */
    public function setItems(array $items)
    {
        $this->_items = $items;
        return $this;
    }

    /**
     * Returns currently set item list.
     *
     * @return array Array of items
     */
    public function getItems()
    {
        return $this->_items;
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        $arrayIterator = new \ArrayIterator($this->_items);

        return $arrayIterator;
    }
}
