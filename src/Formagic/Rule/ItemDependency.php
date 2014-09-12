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
 * Checks if dependencies between given items are matched
 *
 * @package     Formagic\Rule
 * @author      Florian Sonnenburg
 * @since       1.0.0 First time introduced
 **/
class Formagic_Rule_ItemDependency extends Formagic_Rule_Abstract
{
    /**
     * Reuqirement: Dependent item has to have any value
     */
    const VALUE_ANY = true;

    /**
     * Requirement: Dependent item has to have no value
     */
    const VALUE_NONE = '';

    /**
     * Requirement: Either parent item value or dependent item value
     * NOT YET IMPLEMENTED
     * @todo Implement Formagic_Rule_ItemDependency::VALUE_XOR
     */
    const VALUE_XOR = '__FM__VALUE_XOR__';

    /**
     * Condition: Positive value match on requirement ("value is ...")
     */
    const COND_VALUE_EQUALS = 1;

    /**
     * Condition: Negative value match on requirement ("value is not ...")
     */
    const COND_VALUE_NOT_EQUALS = 2;

    /**
     * Default message
     * @var string
     **/
    protected $_message = 'Please enter a value';

    /**
     * Items the validity of this item is dependent on
     * @var Formagic_Item_Abstract
     */
    protected $_item = array();

    /**
     * Check condition
     * @var integer
     */
    protected $_condition;

    /**
     * Required item value
     * @var mixed
     */
    protected $_requirement;

    /**
     * Defines dependent items and validate condition.
     *
     * Supported keys:
     * <dl>
     * <dt>item:</dt><dd>Dependent item</dd>
     * <dt>condition:</dt><dd>Dependency condition. One of the constants
     * Formagic_Rule_ItemDependency::COND_VALUE_EQUALS,
     * Formagic_Rule_ItemDependency::COND_VALUE_NOT_EQUALS</dd>
     * <dt>requirement:</dt><dd>Required value the dependent item is checked
     * against with the specified condition</dd>
     * </dl>
     *
     * Scenario: Given item $item shall be valid if dependent item $dep
     * has a value of 1.
     * <code>
     * $arguments = array(
     *      ['item'] => $dep,
     *      ['condition'] => Formagic_Rule_ItemDependency::COND_VALUE_EQUALS,
     *      ['requirement'] => 1,
     * );
     * </code>
     * @throws Formagic_Exception If key "item", "condition" or "requirement"
     * is not provided or has invalid value
     * @param array $arguments Rule options.
     */
    protected function _init(array $arguments)
    {
        if (!isset($arguments['item'])
            || !($arguments['item'] instanceOf Formagic_Item_Abstract))
        {
            throw new Formagic_Exception('No dependent item defined');
        }

        if (!isset($arguments['condition'])
            || !(in_array($arguments['condition'], array(
                self::COND_VALUE_EQUALS,
                self::COND_VALUE_NOT_EQUALS))))
        {
            throw new Formagic_Exception('No depency condition defined');
        }

        if (!isset($arguments['requirement'])) {
            throw new Formagic_Exception("Missing parameter 'requirement'");
        }

        $this->_item = $arguments['item'];
        $this->_condition = $arguments['condition'];
        $this->_requirement = $arguments['requirement'];
    }

    /**
     * Checks if given item's value matches the specified requirements.
     *
     * @param string $value Value to be checked
     * @return boolean True if conditions are matched
     **/
    public function validate($value)
    {
        // fetch dependent item value
        $itemValue = $this->_item->getValue();

        if (self::VALUE_ANY === $this->_requirement && !empty($itemValue)) {
            // make item value compatible to any value comparsion
            $itemValue = true;
        }

        if (self::COND_VALUE_EQUALS === $this->_condition) {
            $result = ($this->_requirement != $itemValue);
        } else {
            $result = ($this->_requirement == $itemValue);
        }
        return $result;
    }

}