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
 * Interface for Formagic items
 *
 * @category    Formagic
 * @package     Item
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2013 Florian Sonnenburg
 **/
interface Formagic_Item_Interface
{
    /**
     * Returns the HTML string representation of the form item.
     *
     * @return string The HTML string representation.
     */
    public function __toString();

    /**
     * Returns the item name.
     *
     * @return string The item name
     */
    public function getName();

    /**
     * Returns current unfiltered value for this item.
     *
     * @return mixed The unfiltered item value
     */
    public function getUnfilteredValue();

    /**
     * Returns the current filtered value for this item.
     *
     * @return mixed The filtered item value
     */
    public function getValue();

    /**
     * Returns label for this item.
     *
     * @return string The label string.
     */
    public function getLabel();

    /**
     * HTML template for renderers that use HTML-Code.
     * Should be overwritten by subclasses.
     *
     * @return string The HTML string representation of this item.
     **/
    public function getHtml();

    /**
     * Sets the item value to $value.
     *
     * Implements a fluent interface pattern.
     *
     * @param mixed $value The new item value.
     * @return Formagic_Item_Abstract This object.
     */
    public function setValue($value);

    /**
     * Defines which attributes are always to be added to this input element.
     *
     * Default required attributes are "id" and "name".
     *
     * @param array $requiredAttributes Numeric array of required attributes.
     * @return \Formagic_Item_Abstract Fluent interface.
     */
    public function setRequiredAttributes(array $requiredAttributes);

    /**
     * Sets additional attributes for this item.
     *
     * Mainly used for additional HTML attributes other than "name", "id" or
     * "value", such as "style", "class", javascript-handlers etc. Attributes
     * are added corresponding to key->value-pairs in $attArray.
     *
     * setAttributes() will overwrite any previously added item attributes.
     *
     * Implements a fluent interface pattern.
     *
     * @param array $attArray The new
     * @see Formagic_Item_Abstract::addAttribute()
     * @return Formagic_Item_Abstract This object.
     */
    public function setAttributes($attArray);

    /**
     * Adds an HTML attribute to the attributes stack.
     *
     * Implements a fluent interface pattern.
     *
     * @param string $name Attribute name
     * @param string $value Attribute value
     * @return Formagic_Item_Abstract This object.
     */
    public function addAttribute($name, $value);

    /**
     * Returns the attributes array for this item.
     *
     * @see Formagic_Item_Abstract::setAttributes()
     * @see Formagic_Item_Abstract::addAttribute()
     * @return array The attributes array.
     */
    public function getAttributes();

    /**
     * Returns value of an attribute for this item.
     *
     * @param string $name Name of the attribute value to fetch
     * @see Formagic_Item_Abstract::setAttributes()
     * @see Formagic_Item_Abstract::addAttribute()
     * @return string Attribute value
     */
    public function getAttribute($name);

    /**
     * Returns attribute string for HTML tag.
     *
     * Takes the attributes array and transforms it into a string that can be
     * directly inserted into a HTML tag. The string will be built with a
     * trailing space character.
     *
     * Two default attributes are added to the attributes string: "name" and
     * "id". If you want to skip these, add them to your attributes on item
     * generation or via {@link setAttributes()} or {@link addAttribute()} with
     * NULL as value.
     *
     * <code>
     * $item->setAttributes(array('class' => 'myclass', 'onclick' => 'alert('Formagic');'));
     * $string = $item->getAttributeStr();
     * echo '<input type="text"' . $string . ' />';
     * // output will be:
     * // <input type="text" id="item" name="item" class="myclass" onclick="alert('Formagic');" />
     * </code>
     *
     * @see Formagic_Item_Abstract::setAttributes()
     * @see Formagic_Item_Abstract::addAttribute()
     * @return string The attributes string.
     */
    public function getAttributeStr();

    /**
     * Adds rule object to Formagic item
     *
     * Formagic items can have multiple rules which will be applied in the
     * order they are passed to the object.
     *
     * First parameter $rule can either be a string or an instance of a class
     * that extends Formagic_Rule_Abstract.
     * A string value is assumed to be the type of rule to be added.
     *
     * Implements a fluent interface pattern.
     *
     * @param mixed $rule Rule type string or Formagic_Rule_Abstract object.
     * @param array $args Optional array of arguments. Will be passed to the rule constructor as array.
     * @throws Formagic_Exception If no valid role object can be identified.
     * @return Formagic_Item_Abstract This object.
     **/
    public function addRule($rule, array $args = array());

    /**
     * Adds filter object to Formagic item
     *
     * Formagic items can have multiple filters which will be applied in the
     * order they are passed to the object.
     *
     * First parameter $filter can either be a string or an object of a class
     * that extends Formagic_Filter_Interface.
     * A string value is assumed to be the type of filter to be added.
     *
     * This method throws an exception if no valid role object can be identified.
     *
     * Implements a fluent interface pattern.
     *
     * @param mixed $filter Filter type string or Formagic_Filter_Interface object.
     * @param array $args Optional array of arguments. Will be passed to the filter constructor as array.
     * @throws Formagic_Exception
     * @return Formagic_Item_Abstract Fluent interface
     **/
    public function addFilter($filter, array $args = array());

    /**
     * Checks if a specific filter is defined for a Formagic item.
     *
     * @param string $filterName Filter name
     * @return boolean Check value
     */
    public function hasFilter($filterName);

    /**
     * Tells if a rule exists for this item.
     *
     * The $ruleName parameter has to be a string with the name of rule that is
     * looked for. If you want to know for example if the item has the
     * mandatory rule added, $ruleName would have to be 'mandatory'.
     *
     * The search string is case insensitive.
     *
     * <code>
     * <?php
     * // add a rule to the item object
     * $mandatory = new Formagic_Rule_Mandatory();
     * $item->addRule($mandatory);
     *
     * // look for the rule
     * $ruleExists = $item->hasRule('mandatory'); // would return TRUE
     * $ruleExists = $item->hasRule('Mandatory'); // would return TRUE
     * $ruleExists = $item->hasRule('Formagic_Rule_Mandatory'); // would return FALSE
     * ?>
     * </code>
     *
     * @param string $ruleName Rule name, eg. 'Mandatory'
     * @return boolean
     */
    public function hasRule($ruleName);

    /**
     * Performs rule checks
     *
     * Iterates through all defined rules of Formagic item. Returns true if all
     * rules apply or false otherwise.
     *
     * @return boolean The validation result.
     */
    public function validate();

    /**
     * Returns array of violated rules.
     *
     * If no rules were violated or if no validation has been performed yet,
     * an empty array will be returned.
     *
     * @return array The violated rules.
     */
    public function getViolatedRules();

    /**
     * Sets readonly flag
     *
     * Implements a fluent interface pattern.
     *
     * @param boolean $flag Readonly status flag.
     * @return Formagic_Item_Abstract This object.
     */
    public function setReadonly($flag);

    /**
     * Sets hidden flag for item.
     *
     * Implements a fluent interface pattern.
     *
     * @param boolean $flag Hidden status flag.
     * @return Formagic_Item_Abstract This object.
     */
    public function setHidden($flag);

    /**
     * Returns hidden status of item
     *
     * @return boolean The hidden status.
     */
    public function isHidden();

    /**
     * Defines if the item will be ignored in form submit.
     *
     * Implements a fluent interface pattern.
     *
     * @param boolean $flag Ignored status flag.
     * @return Formagic_Item_Abstract This object.
     */
    public function setIgnore($flag);

    /**
     * Returns ignore status of item
     *
     * @return boolean The ignored status.
     */
    public function isIgnored();

    /**
     * Sets disabled flag for item and removes it from form
     *
     * Implements a fluent interface pattern.
     *
     * @param boolean $flag Defined item's disabled status.
     * @return Formagic_Item_Abstract Fluent interface
     */
    public function setDisabled($flag);

    /**
     * Returns disabled status of item
     *
     * @return boolean The disabled status.
     */
    public function isDisabled();

    /**
     * Sets isFixed flag.
     *
     * If set to true, all following calls to setValue() will
     * be ignored.
     *
     * Implements a fluent interface pattern.
     *
     * @param boolean $flag IsFixed flag value.
     * @return Formagic_Item_Abstract This object.
     */
    public function setFixed($flag);
}