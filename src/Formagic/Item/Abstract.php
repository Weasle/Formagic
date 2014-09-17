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
 * Abstract superclass for Formagic items
 *
 * @package     Formagic\Item
 * @author      Florian Sonnenburg
 * @since       2007 First time introduced
 **/
abstract class Formagic_Item_Abstract
{
    /**
     * Filtered item value cache
     * @var mixed
     */
    private $_filteredValue;

    /**
     * Violated rule after validation
     * @var Formagic_Rule_Abstract
     */
    private $_violatedRules = array();

    /**
     * Form item name
     * @var string
     */
    protected $_name;

    /**
     * Item value
     * @var mixed
     */
    protected $_value;

    /**
     * Form item label
     * @var boolean
     */
    protected $_label = '';

    /**
     * Additional attributes for item HTML tag
     * @var array
     */
    protected $_attributes = array();

    /**
     * Array of required attributes for this item
     * @var array
     */
    protected $_requiredAttributes = array('id', 'name');

    /**
     * Array of rule object that are applied for this item
     * @var array
     */
    protected $_rules = array();

    /**
     * Array of input filters for this item
     * @var array
     **/
    protected $_filters = array();

    /**
     * Determines if item content can be edited.
     * @var boolean
     */
    protected $_isReadonly = false;

    /**
     * Determines if item will be displayed
     * @var boolean
     */
    protected $_isHidden = false;

    /**
     * Determines if item is removed from Formagic form.
     * @var boolean
     */
    protected $_isDisabled = false;

    /**
     * Determines if item content should be interpreted after submit.
     * @var boolean
     */
    protected $_isIgnored = false;

    /**
     * Determines if value can be changed.
     * @var boolean
     */
    protected $_isFixed = false;

    /**
     * Keyword to determine what kind of item is represented by the current item class
     * @var string
     */
    protected $type = 'undefined';

    /**
     * Constructor
     *
     * @param string $name Name of item
     * @param array $arguments Additional arguments
     *
     * @throws Formagic_Exception
     **/
    public function __construct($name, array $arguments = array())
    {
        $this->_name = $name;

        $additionalArgs = array();
        foreach ($arguments as $key => $arg) {
            switch($key) {
                case 'ignore':
                    $this->_isIgnored = $arg;
                    break;
                case 'disable':
                    $this->_isDisabled = $arg;
                    break;
                case 'label':
                    $this->_label = $arg;
                    break;
                case 'value':
                    $this->_value = $arg;
                    break;
                case 'attributes':
                    $this->setAttributes($arg);
                    break;
                case 'hidden':
                    $this->_isHidden = $arg;
                    break;
                case 'readonly':
                    $this->_isReadonly = $arg;
                    break;
                case 'fixed':
                    $this->setFixed($arg);
                    break;
                case 'rules':
                    if (!is_array($arg)) {
                        $this->addRule($arg);
                    } else {
                        foreach($arg as $rule) {
                            $this->addRule($rule);
                        }
                    }
                    break;
                case 'filters':
                    if (!is_array($arg)) {
                        $this->addFilter($arg);
                    } else {
                        foreach($arg as $filter => $args) {
                            if (is_numeric($filter)) {
                                $filter = $args;
                                $args = array();
                            }
                            $this->addFilter($filter, $args);
                        }
                    }
                    break;
                default:
                    // Argument handler for unknown arguments. Defined in item classes
                    $additionalArgs[$key] = $arg;
            } // switch
        }
        $this->_init($additionalArgs);
    }

    /**
     * Allow subclass initialization.
     *
     * @param array $additionalArgs Array of arguments that are not processed by superclass.
     * @return void
     */
    protected function _init($additionalArgs)
    {
    }

    /**
     * Returns item type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Prints item infos.
     *
     * @return string The item information string
     **/
    public function printInfo()
    {
        $str = "<strong>Item {$this->_name}</strong><br />\n" .
               "Type: " . get_class($this) . "<br />\n" .
               "Value: '{$this->_value}'<br />\n" .
               "Flags:
                    hidden '{$this->_isHidden}',
                    readonly '{$this->_isReadonly}',
                    disabled '{$this->_isDisabled}',
                    ignored '{$this->_isIgnored}',
                    fixed '{$this->_isFixed}',<br />\n";
        echo $str;
    }

    /**
     * Returns the HTML string representation of the form item.
     *
     * @return string The HTML string representation.
     */
    public function __toString()
    {
        return $this->getHtml();
    }

    /**
     * Returns the item name.
     *
     * @return string The item name
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Returns current unfiltered value for this item.
     *
     * @return mixed The unfiltered item value
     */
    public function getUnfilteredValue()
    {
        return $this->_value;
    }

    /**
     * Returns the current filtered value for this item.
     *
     * @return string The filtered item value
     */
    public function getValue()
    {
        if (!isset($this->_filteredValue)) {

            // chain output of filters together
            $this->_filteredValue = $this->_value;
            foreach ($this->_filters as $filter) {
                $this->_filteredValue = $this->_filterValue($filter, $this->_filteredValue);
            }
        }

        return $this->_filteredValue;
    }

    /**
     * Filters a value
     *
     * @param Formagic_Filter_Interface $filter Filter object
     * @param mixed $subject Scalar or array
     *
     * @throws Formagic_Exception if subject is not supported
     *
     * @return mixed Filtered scalar or array
     */
    protected function _filterValue(Formagic_Filter_Interface $filter, $subject)
    {
        if (is_scalar($subject) || $subject === null) {
            $filteredValue = $filter->filter($subject);
        } elseif (is_array($subject)) {
            $filteredValue = array();
            foreach ($subject as $key => $value) {
                $filteredValue[$key] = $this->_filterValue($filter, $value);
            }
        } else {
            throw new Formagic_Exception('Invalid value type: ' . gettype($subject));
        }

        return $filteredValue;
    }

    /**
     * Returns label for this item.
     *
     * @return string The label string.
     */
    public function getLabel()
    {
        return $this->_label;
    }

    /**
     * HTML template for renderers that use HTML-Code.
     * Should be overwritten by subclasses.
     *
     * @return string The HTML string representation of this item.
     **/
    public function getHtml()
    {
        return "";
    }

    /**
     * Sets the item value to $value.
     *
     * Implements a fluent interface pattern.
     *
     * @param mixed $value The new item value.
     * @return Formagic_Item_Abstract This object.
     */
    public function setValue($value)
    {
        if (!$this->_isFixed) {
            $this->_value = $value;
            $this->_filteredValue = null;
        }
        return $this;
    }

    /**
     * Defines which attributes are always to be added to this input element.
     *
     * Default required attributes are "id" and "name".
     *
     * @param array $requiredAttributes Numeric array of required attributes.
     * @return \Formagic_Item_Abstract Fluent interface.
     */
    public function setRequiredAttributes(array $requiredAttributes)
    {
        $this->_requiredAttributes = $requiredAttributes;
        return $this;
    }

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
    public function setAttributes($attArray)
    {
        $this->_attributes = $attArray;
        return $this;
    }

    /**
     * Adds an HTML attribute to the attributes stack.
     *
     * Implements a fluent interface pattern.
     *
     * @param string $name Attribute name
     * @param string $value Attribute value
     * @return Formagic_Item_Abstract This object.
     */
    public function addAttribute($name, $value)
    {
        $this->_attributes[$name] = $value;
        return $this;
    }

    /**
     * Returns the attributes array for this item.
     *
     * @see Formagic_Item_Abstract::setAttributes()
     * @see Formagic_Item_Abstract::addAttribute()
     * @return array The attributes array.
     */
    public function getAttributes()
    {
        $attributes = $this->_attributes;
        foreach($this->_requiredAttributes as $requiredAttribute) {
            if(!array_key_exists($requiredAttribute, $attributes)) {
                $value = $this->getAttribute($requiredAttribute);
                $attributes[$requiredAttribute] = $value;
            }
        }

        return $attributes;
    }

    /**
     * Returns value of an attribute for this item.
     *
     * @param string $name Name of the attribute value to fetch
     * @see Formagic_Item_Abstract::setAttributes()
     * @see Formagic_Item_Abstract::addAttribute()
     * @return string Attribute value
     */
    public function getAttribute($name)
    {
        switch($name) {
            case 'name':
                if (array_key_exists('name', $this->_attributes)) {
                    $result = $this->_attributes['name'];
                } else {
                    $result = $this->_name;
                }
                break;
            case 'id':
                if (array_key_exists('id', $this->_attributes)) {
                    $result = $this->_attributes['id'];
                } else {
                    $result = $this->_makeDomId($this->getAttribute('name'));
                }
                break;
            default:
                if (!array_key_exists($name, $this->_attributes)) {
                    return null;
                }
                $result = $this->_attributes[$name];
        }
        return $result;
    }

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
    public function getAttributeStr()
    {
        $attributes = $this->getAttributes();
        return $this->_buildAttributeStr($attributes);
    }

    /**
     * Assembles attribute string in HTML-conform style
     *
     * @param array $attributes array of attributes
     * @return string Attributes string
     */
    protected function _buildAttributeStr(array $attributes)
    {
        $res = "";
        foreach($attributes as $key => $att) {
            if (null === $att) {
                continue;
            }
            $res .= " $key=\"$att\"";
        }
        return $res;
    }

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
    public function addRule($rule, array $args = array())
    {
        // argument is assumed rule type if string.
        if (is_string($rule)) {
            $class = 'Formagic_Rule_' . ucFirst($rule);
            $this->_rules[$class] = new $class($args);
        } elseif($rule instanceof Formagic_Rule_Abstract) {
            $type = get_class($rule);
            $this->_rules[$type] = $rule;
        } else {
            throw new Formagic_Exception('Invalid rule type or rule object');
        }
        return $this;
    }

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
    public function addFilter($filter, array $args = null)
    {
        // argument is assumed rule type if string.
        if (is_string($filter)) {
            $class = 'Formagic_Filter_' . ucFirst($filter);
            $this->_filters[$class] = new $class($args);
        } elseif($filter instanceof Formagic_Filter_Interface) {
            $type = get_class($filter);
            $this->_filters[$type] = $filter;
        } else {
            throw new Formagic_Exception('Invalid filter type or filter object');
        }
        return $this;
    }

    /**
     * Checks if a specific filter is defined for a Formagic item.
     *
     * @param string $filterName Filter name
     * @return boolean Check value
     */
    public function hasFilter($filterName)
    {
        $filterKey = 'Formagic_Filter_' . ucFirst($filterName);
        return isset($this->_filters[$filterKey]);
    }

    /**
     * Returns specified filter object if added to the item.
     *
     * @param string $filterName
     * @throws Formagic_Exception If the filter is not added to the item
     * @return Formagic_Filter_Interface Filter instance
     */
    public function getFilter($filterName)
    {
        if (!$this->hasFilter($filterName)) {
            throw new Formagic_Exception('Filter with name ' . $filterName . ' is not added to this item');
        }

        $fullyQualifiedFilterName = 'Formagic_Filter_' . ucFirst($filterName);
        return $this->_filters[$fullyQualifiedFilterName];
    }

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
    public function hasRule($ruleName)
    {
        $ruleName = 'Formagic_Rule_' . ucFirst($ruleName);
        return isset($this->_rules[$ruleName]);
    }

    /**
     * Returns specified rule object if added to the item.
     *
     * @param string $ruleName
     * @throws Formagic_Exception If the rule is not added to the item
     * @return Formagic_Rule_Abstract Rule instance
     */
    public function getRule($ruleName)
    {
        if (!$this->hasRule($ruleName)) {
            throw new Formagic_Exception('Rule with name ' . $ruleName . ' is not added to this item');
        }

        $fullyQualifiedRuleName = 'Formagic_Rule_' . ucFirst($ruleName);
        return $this->_rules[$fullyQualifiedRuleName];
    }

    /**
     * Performs rule checks
     *
     * Iterates through all defined rules of Formagic item. Returns true if all
     * rules apply or false otherwise.
     *
     * @return boolean The validation result.
     */
    public function validate()
    {
        $this->_violatedRules = array();
        foreach($this->_rules as $rule) {
            if (!$this->_validateItemValue($rule, $this->getValue())) {
                $this->_violatedRules[] = $rule;
            }
        }
        $result = count($this->_violatedRules) ? false : true;
        return $result;
    }

    /**
     * Perform validation on item value.
     *
     * @param Formagic_Rule_Abstract $rule Validation rule object
     * @param string|array|null $subject Validation subject
     *
     * @throws Formagic_Exception if $subject does not have any supported type
     *
     * @return boolean Validation result
     */
    protected function _validateItemValue(Formagic_Rule_Abstract $rule, $subject)
    {
        // @todo adjust unit test
        if (is_string($subject) || is_null($subject)) {
            return $rule->validate($subject);
        } elseif (is_array($subject)) {
            foreach($subject as $value) {
                if(!$this->_validateItemValue($rule, $value)) {
                    return false;
                }
            }
            return true;
        } else {
            throw new Formagic_Exception('Invalid value type');
        }
    }

    /**
     * Returns array of violated rules.
     *
     * If no rules were violated or if no validation has been performed yet,
     * an empty array will be returned.
     *
     * @return array The violated rules.
     */
    public function getViolatedRules()
    {
        return $this->_violatedRules;
    }

    /**
     * Sets readonly flag
     *
     * Implements a fluent interface pattern.
     *
     * @param boolean $flag Readonly status flag.
     * @return Formagic_Item_Abstract This object.
     */
    public function setReadonly($flag)
    {
        $this->_isReadonly = $flag;
        return $this;
    }

    /**
     * Sets hidden flag for item.
     *
     * Implements a fluent interface pattern.
     *
     * @param boolean $flag Hidden status flag.
     * @return Formagic_Item_Abstract This object.
     */
    public function setHidden($flag)
    {
        $this->_isHidden = $flag;
        return $this;
    }

    /**
     * Returns hidden status of item
     *
     * @return boolean The hidden status.
     */
    public function isHidden()
    {
        return $this->_isHidden;
    }

    /**
     * Defines if the item will be ignored in form submit.
     *
     * Implements a fluent interface pattern.
     *
     * @param boolean $flag Ignored status flag.
     * @return Formagic_Item_Abstract This object.
     */
    public function setIgnore($flag)
    {
        $this->_isIgnored = $flag;
        return $this;
    }

    /**
     * Returns ignore status of item
     *
     * @return boolean The ignored status.
     */
    public function isIgnored()
    {
        return $this->_isIgnored;
    }

    /**
     * Sets disabled flag for item and removes it from form
     *
     * Implements a fluent interface pattern.
     *
     * @param boolean $flag Defined item's disabled status.
     * @return Formagic_Item_Abstract Fluent interface
     */
    public function setDisabled($flag)
    {
        $this->_isDisabled = $flag;
        return $this;
    }

    /**
     * Returns disabled status of item
     *
     * @return boolean The disabled status.
     */
    public function isDisabled()
    {
        return $this->_isDisabled;
    }

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
    public function setFixed($flag)
    {
        $this->_isFixed = (bool)$flag;
        return $this;
    }

    /**
     * Takes a string and returns a valid DOM ID.
     *
     * @param string $str String to convert to a DOM ID
     * @return string Valid DOM ID
     */
    private function _makeDomId($str)
    {
        $id = preg_replace(
            '/[^a-zA-Z0-9\-]/',
            '-',
            $str
        );
        $id = rtrim($id, '-');
        return $id;
    }
}
