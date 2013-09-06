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
 * @copyright   Copyright (c) 2009 Florian Sonnenburg
 * @license     http://www.formagic-php.net/license-agreement/   New BSD License
 */

/**
 * Formagic Container Item class
 *
 * Changes on following flag will be applied recursively to all items added to
 * the container object:
 *  - _isHidden
 *  - _isReadonly
 *  - _isDisabled
 *
 * Changes on the flag _isPostItem will only be applied to the container item
 * itself (and defaults to false as there is no use posting a container item).
 *
 * @category    Formagic
 * @package     Item
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2011 Florian Sonnenburg
 * @version     $Id: Container.php 173 2012-05-16 13:19:22Z meweasle $
 */
class Formagic_Item_Container extends Formagic_Item_Abstract implements IteratorAggregate, Countable
{
    /**
     * Pointer to items array section of formagic object
     * @var string
     **/
    protected $_items = array();

    /**
     * Container headline
     * @var string
     **/
    public $headline = '';

    /**
     * Define what attributes are required for default container item
     * @var array
     */
    protected $_requiredAttributes = array('id');

    /**
     * Adds formagic item object to array of items.
     *
     * Creates new item object and adds this, or adds passed formagicItem object
     *
     * @param Formagic_Item_Abstract|String $item String with item type or
     *        Formagic_Item_Abstract object
     * @param string $name String with item name. NULL if $type
     *        is Formagic_Item_Abstract object
     * @param array $args Array with additional item information. NULL if $type
     *        is Formagic_Item_Abstract object
     * @return Formagic_Item_Container Fluent interface
     */
    public function addItem($item, $name=null, array $args=null)
    {
        if ($item instanceOf Formagic_Item_Abstract) {
            $name = $item->getName();
        } else {
            if (!$name) {
                throw new Formagic_Exception('Name string required for new items');
            }
            // hand down status flags to added items
            if ($this->_isHidden) {
                $args['hidden'] = true;
            }
            if ($this->_isReadonly) {
                $args['readonly'] = true;
            }
            if ($this->_isDisabled) {
                $args['disable'] = true;
            }
            $item = Formagic::createItem($item, $name, $args);
        }

        if ($this->_rules) {
            foreach ($this->_rules as $rule) {
                $item->addRule($rule);
            }
        }
        if ($this->_filters) {
            foreach ($this->_filters as $filter) {
                $item->addFilter($filter);
            }
        }

        $this->_items[$item->getName()] = $item;
        return $this;
    }

    /**
     * Counts items added to self and all sub-containers.
     *
     * @return integer The number of items in this container
     */
    public function count()
    {
        $count = 0;
        foreach($this->_items as $item) {
            if ($item instanceOf Formagic_Item_Container) {
                $count += $item->count();
            } else {
                $count += 1;
            }
        }
        return $count;
    }

    /**
     * Returns items array
     *
     * @return array
     */
    public function getItems()
    {
        return $this->_items;
    }

    /**
     * Returns added item $name.
     *
     * Throws Formagic_Exception by default if item does not exist. This can be
     * altered by setting $throwException to FALSE; the result value will be
     * NULL then.
     *
     * @throws Formagic_Exception
     * @param string $name Item identifier string.
     * @param boolean $throwException Returns NULL if set to FALSE.
     * @return Formagic_Item_Abstract Returns the Formagic item identified by
     *      $name or throws exception if not found.
     */
    public function getItem($name, $throwException = true)
    {
        $res = false;
        if (isset($this->_items[$name])) {
            $res = $this->_items[$name];
        } else {
            foreach ($this->_items as $item) {
                if ($item instanceOf Formagic_Item_Container) {
                    $res = $item->getItem($name, false);
                    if ($res) {
                        break;
                    }
                }
            }
        }
        if (!$res) {
            if ($throwException) {
                throw new Formagic_Exception("Item '$name' does not exist");
            } else {
                $res = null;
            }
        }
        return $res;
    }

    /**
     * Sets new values for all items assigned to this container.
     *
     * Other than in Formagic_Item_Abstract defined, $value has to be an
     * associative array, the key beeing the name of the item the value belongs
     * to.
     *
     * Implements a fluent interface pattern.
     *
     * @param array $value Set of new values for contained items.
     * @return Formagic_Item_Container This object.
     */
    public function setValue($value)
    {
        // check that $value is array
        if(!is_array($value)) {
            throw new Formagic_Exception('Container value has to be an array');
        }

        // set values to all registered items
        foreach ($this->_items as $item) {
            // delegate to sub-containers
            if ($item instanceOf Formagic_Item_Container) {
                $item->setValue($value);

            // special treatment for image type submit
            } elseif($item instanceOf Formagic_Item_ImageSubmit) {
                if (array_key_exists($item->getName() . '_x', $value)
                    && array_key_exists($item->getName() . '_y', $value)
                ) {
                    $clickCoordiantes = array(
                        'x' => (int)$value[$item->getName() . '_x'],
                        'y' => (int)$value[$item->getName() . '_y']
                    );
                    $item->setClickCoordinates($clickCoordiantes);
                    $item->setValue($item->getLabel());
                }

            // everything else
            } else {
                if (array_key_exists($item->getName(), $value)) {
                    $item->setValue($value[$item->getName()]);

                // do not clear value if item has one set already
                } else {
                    $itemValue = $item->getValue();
                    if(empty($itemValue)) {
                        $item->setValue(null);
                    }
                }
            }
        }
        return $this;
    }

    /**
     * Adds a rule to this container and all contained items.
     *
     * @see Formagic_Item_Abstract::addRule()
     * @param mixed $rule Rule type string or Formagic_Rule_Abstract object.
     * @param array $args Optional array of arguments. Will be passed to the
     *        rule constructor as array.
     * @throws Formagic_Exception if $rule argument is invalid
     * @return Formagic_Item_Container This object.
     */
    public function addRule($rule, array $args=array())
    {
        parent::addRule($rule, $args);
        foreach ($this->_items as $item) {
            $item->addRule($rule, $args);
        }
    }

    /**
     * Adds a filter to this container and all contained items.
     *
     * @see Formagic_Item_Abstract::addFilter()
     * @param mixed $filter Filter type string or Formatic_Filter_Interface object.
     * @param array $args Optional array of arguments. Will be passed to the
     *        filter constructor as array.
     * @throws Formagic_Exception if $filter argument is invalid
     * @return Formagic_Item_Container This object
     */
    public function addFilter($filter, array $args=null)
    {
        parent::addFilter($filter, $args);
        foreach ($this->_items as $item) {
            $item->addFilter($filter, $args);
        }
    }

    /**
     * Sets readonly flag to container item and all its descendants.
     *
     * Implements a fluent interface pattern.
     *
     * @param boolean $flag Readonly status flag.
     * @see Formagic_Item_Abstract::setReadonly()
     * @return Formagic_Item_Container This object.
     */
    public function setReadonly($flag)
    {
        $this->_isReadonly = $flag;
        foreach($this->getItems() as $item) {
            $item->setReadonly($flag);
        }
        return $this;
    }

    /**
     * Sets hidden flag on container item and all its descendants.
     *
     * Implements a fluent interface pattern.
     *
     * @param boolean $flag Hidden status flag.
     * @see Formagic_Item_Abstract::setHidden()
     * @return Formagic_Item_Container This object.
     */
    public function setHidden($flag)
    {
        $this->_isHidden = $flag;
        foreach($this->getItems() as $item) {
            $item->setHidden($flag);
        }
        return $this;
    }

    /**
     * Disables container item and all its descendants and thus removes all
     * involved items from form.
     *
     * Implements a fluent interface pattern.
     *
     * @param boolean $flag Disabled status flag.
     * @see Formagic_Item_Abstract::setDisabled()
     * @return Formagic_Item_Container This object.
     */
    public function setDisabled($flag)
    {
        $this->_isDisabled = $flag;
        foreach($this->getItems() as $item) {
            $item->setDisabled($flag);
        }
        return $this;
    }

    /**
     * Returns values of all stored items.
     *
     * The return value will be an associative array, the keys beeing the name
     * of the item, the value beeing the filtered item value.
     *
     * @return array Array of item values.
     */
    public function getValue()
    {
        $res = array();
        foreach ($this->getItems() as $item) {
            if ($item->isIgnored()) {
                continue;
            }

            $value = $item->getValue();
            if ($item instanceOf Formagic_Item_Container) {
                $res = $res + $value;
            } else {
                $res[$item->getName()] = $item->getValue();
            }
        }
        return $res;
    }

   /**
     * Validates contained items.
     *
     * Iterates through all contained items. If any rule is violated, sub-item
     * will return violated Formagic_Rule item and the container will pass it
     * on.
     *
     * If all items pass violation, validate() calls onValidate-handler and
     * returns its result.
     *
     * @return boolean
     */
    public function validate()
    {
        $valid = true;
        foreach($this->_items as $item) {
            if (!$item->validate()) {
                $valid = false;
            }
        }
        return $valid;
    }

    /**
     * Completes the IteratorAggregate interface.
     *
     * @return ArrayObject ArrayObject with container items.
     */
    public function getIterator()
    {
        return new ArrayObject($this->_items);
    }
}