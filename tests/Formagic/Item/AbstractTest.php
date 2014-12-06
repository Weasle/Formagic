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
 * Tests Formagic item public interface
 *
 * @package     Formagic\Tests
 * @author      Florian Sonnenburg
 **/
class Formagic_Item_Abstract_Test extends PHPUnit_Framework_TestCase
{
    /**
     * Tests that printInfo() prints out a string to stdOut
     */
    public function testPrintInfo()
    {
        $input = new Formagic_Item_Mock_MockItem('test');
        ob_start();
        $res = $input->printInfo();
        $this->assertNull($res);
        $info = ob_get_clean();
        $this->assertInternalType('string', $info);
    }

    /**
     * Tests that magic __toString method returns HTML string
     */
    public function testToString()
    {
        $input      = new Formagic_Item_Mock_MockItem('test');
        $toString   = $input->__toString();
        $this->assertInternalType('string', $toString);

        $html = $input->getHtml();
        $this->assertSame($toString, $html);
    }

    /**
     * Tests that getName() returns item's name string
     */
    public function testGetName()
    {
        $name       = 'part1-part2_part3[test][]';
        $input      = new Formagic_Item_Mock_MockItem($name);
        $actualName = $input->getName();
        $this->assertSame($name, $actualName);
    }

    /**
     * Tests that getFilter() returns previously added filter
     */
    public function testGetFilter()
    {
        // add as instance
        $filter = new Formagic_Filter_Mock_MockFilter();
        $input  = new Formagic_Item_Mock_MockItem('test', array('filters' => $filter));

        $actual = $input->getFilter('Mock_MockFilter');
        $this->assertSame($filter, $actual);
    }

    /**
     * Tests that getFilter() throws exception if filter is not found
     *
     * @expectedException Formagic_Exception
     */
    public function testGetFilterException()
    {
        // add as instance
        $input  = new Formagic_Item_Mock_MockItem('test');
        $input->getFilter('FilterNotExists');
    }

    /**
     * Tests that filter can be added.
     */
    public function testAddFilter()
    {
        // add as instance
        $filter = new Formagic_Filter_Mock_MockFilter();
        $input  = new Formagic_Item_Mock_MockItem('test');
        $input->addFilter($filter);
        $hasFilter = $input->hasFilter('Mock_MockFilter');
        $this->assertEquals($hasFilter, true);

        // add as instance on instantiation, single
        $input  = new Formagic_Item_Mock_MockItem('test', array('filters' => $filter));
        $hasFilter = $input->hasFilter('Mock_MockFilter');
        $this->assertEquals($hasFilter, true);

        // add as instance on instantiation, multi
        $filter2 = new Formagic_Filter_Mock_MockFilter2();
        $input  = new Formagic_Item_Mock_MockItem('test', array(
            'filters' => array($filter, $filter2)
        ));
        $hasFilter = $input->hasFilter('Mock_MockFilter');
        $hasFilter2 = $input->hasFilter('Mock_MockFilter2');
        $this->assertEquals($hasFilter, true);
        $this->assertEquals($hasFilter2, true);

        // add as string
        $input->addFilter('Mock_MockFilter');
        $hasFilter = $input->hasFilter('Mock_MockFilter');
        $this->assertEquals($hasFilter, true);

        // add as string on instantiation, single
        $input  = new Formagic_Item_Mock_MockItem('test', array(
            'filters' => 'Mock_MockFilter'));
        $hasFilter = $input->hasFilter('Mock_MockFilter');
        $this->assertEquals($hasFilter, true);

        // add as string on instantiation, multi
        $input  = new Formagic_Item_Mock_MockItem('test', array(
            'filters' => array('Mock_MockFilter', 'Mock_MockFilter2')));
        $hasFilter = $input->hasFilter('Mock_MockFilter');
        $hasFilter2 = $input->hasFilter('Mock_MockFilter2');
        $this->assertEquals($hasFilter, true);
        $this->assertEquals($hasFilter2, true);
    }

    /**
     * Test that setting something other than string or filter object throws
     * an exception.
     *
     * @expectedException Formagic_Exception
     */
    public function testAddFilterException()
    {
        $input  = new Formagic_Item_Mock_MockItem('test');
        $input->addFilter(null);
    }

    /**
     * Tests that setValue implements fluent interface
     */
    public function testSetValue()
    {
        $input = new Formagic_Item_Mock_MockItem('test');
        $res = $input->setValue('');
        $this->assertSame($input, $res);
    }

    /**
     * Tests that previously set value is returned without modification by
     * getUnfilteredValue()
     */
    public function testGetUnfilteredValue()
    {
        // set value explicitly
        $value = 'a string';
        $input = new Formagic_Item_Mock_MockItem('test');
        $actualValue = $input
            ->setValue($value)
            ->getUnfilteredValue();
        $this->assertSame($value, $actualValue);

        // set value on instantiation
        $input = new Formagic_Item_Mock_MockItem('test', array('value' => $value));
        $actualValue = $input->getUnfilteredValue();
        $this->assertSame($value, $actualValue);
    }

    /**
     * Tests that getValue returns previously set value and applies filters
     * on returned value
     */
    public function testGetValue()
    {
        /**
         * no filter set
         */
        // set value explicitly
        $value = 'a string';
        $input = new Formagic_Item_Mock_MockItem('test');
        $res = $input->setValue($value);
        $this->assertSame($input, $res);

        $actualValue = $input->getValue();
        $this->assertSame($value, $actualValue);

        // set value on instantiation
        $input = new Formagic_Item_Mock_MockItem('test', array('value' => $value));
        $actualValue = $input->getValue();
        $this->assertSame($value, $actualValue);

        /**
         * filtered value
         */
        $input = new Formagic_Item_Mock_MockItem('test', array(
            'value'   => $value,
            'filters' => 'Mock_MockFilter'
        ));
        $actualValue = $input->getValue();
        $this->assertNotSame($value, $actualValue);
        $this->assertSame(Formagic_Filter_Mock_MockFilter::FILTERED_VALUE, $actualValue);
    }
    
    /**
     * Tests that internal class cache for filtered value returns same value 
     * when called multiple times
     */
    public function testGetFilteredValueMultipleTimes()
    {
        $value = 'a string';
        $input = new Formagic_Item_Mock_MockItem('test', array(
            'value'   => $value,
            'filters' => 'Mock_MockFilter'
        ));
        $expected = $input->getValue();
        $actual = $input->getValue();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Tests that item filtering throws an exception if value type is no array
     * or string or integer
     * 
     * @expectedException Formagic_Exception
     */
    public function testInvalidItemTypeForFilteringException()
    {
        $value = new stdClass();
        $input = new Formagic_Item_Mock_MockItem('test', array(
            'value'   => $value,
            'filters' => 'Mock_MockFilter'
        ));
        $input->getValue();
    }

    /**
     * Tests that label is set by option and returned by getLabel()
     */
    public function testGetLabel()
    {
        $itemLabel = 'testLabel';
        $item = new Formagic_Item_Mock_MockItem('testItem', array('label' => $itemLabel));
        $actualLabel = $item->getLabel();
        $this->assertSame($itemLabel, $actualLabel);
    }

    /**
     * Tests that parent class implementation of getHtml() returns empty string
     */
    public function testGetHtmlFromAbstractClass()
    {
        $item = new Formagic_Item_Mock_MockItem('testItem');
        $actualHtml = $item->getParentHtml();
        $this->assertSame('', $actualHtml);
    }

    /**
     * Tests that setAttributes() implements fluent interface
     */
    public function testSetAttributes()
    {
        $item = new Formagic_Item_Mock_MockItem('testItem');
        $res = $item->setAttributes(array('a' => 'b'));
        $this->assertSame($item, $res);
    }

    
    /**
     * Tests that addAttributes() implements fluent interface
     */
    public function testAddAttribute()
    {
        $item = new Formagic_Item_Mock_MockItem('testItem');
        $res = $item->addAttribute('a', 'b');
        $this->assertSame($item, $res);
    }
    
    /**
     * Tests getting non-existent attribute
     */
    public function testGetAttribute()
    {
        $item = new Formagic_Item_Mock_MockItem('testItem');
        $actual = $item->getAttribute('n/a');
        $this->assertSame(null, $actual);
        
        $expectedName  = 'testAttribute';
        $expectedValue = 'testValue';
        $actualValue = $item
            ->addAttribute($expectedName, $expectedValue)
            ->getAttribute($expectedName);
        $this->assertSame($expectedValue, $actualValue);
    }

    /**
     * Tests that ID attribute can be set manually
     */
    public function testGetIdAttribute()
    {
        $itemId = 'myId';
        $item = new Formagic_Item_Mock_MockItem('testItem');
        $item->addAttribute('id', $itemId);
        
        $actual = $item->getAttribute('id');
        $this->assertSame($itemId, $actual);
    }

    /**
     * Tests that name attribute can be set independently from item name
     */
    public function testGetNameAttribute()
    {
        $itemName = 'itemName';
        $itemHtmlName = 'itemHtmlName[]';
        $item = new Formagic_Item_Mock_MockItem($itemName);
        $item->addAttribute('name', $itemHtmlName);
        
        $actual = $item->getAttribute('name');
        $this->assertNotEquals($itemName, $actual);
        $this->assertEquals($itemHtmlName, $actual);
    }

    /**
     * Tests setting and getting attributes
     */
    public function testGetAttributes()
    {
        $attrName  = 'class';
        $attrValue = 'test';
        $attrValueChanged = 'testNew';

        // set by method
        $item = new Formagic_Item_Mock_MockItem('testItem');
        $attributes = $item
            ->setAttributes(array($attrName => $attrValue))
            ->getAttributes();
        $this->assertInternalType('array', $attributes);
        $this->assertArrayHasKey($attrName, $attributes);
        $this->assertContains($attrValue, $attributes);

        // set by options
        $item = new Formagic_Item_Mock_MockItem('testItem', array(
            'attributes' => array($attrName => $attrValue)
        ));
        $attributes = $item->getAttributes();
        $this->assertArrayHasKey($attrName, $attributes);
        $this->assertContains($attrValue, $attributes);

        // set new attributes
        $attributes = $item
            ->setAttributes(array($attrName => $attrValueChanged))
            ->getAttributes();
        $this->assertArrayHasKey($attrName, $attributes);
        $this->assertNotContains($attrValue, $attributes);
        $this->assertContains($attrValueChanged, $attributes);

        // add attribute
        $item = new Formagic_Item_Mock_MockItem('testItem');
        $attributes = $item
            ->addAttribute($attrName, $attrValue)
            ->getAttributes();
        $this->assertArrayHasKey($attrName, $attributes);
        $this->assertContains($attrValue, $attributes);

        // overwrite single attribute
        $item = new Formagic_Item_Mock_MockItem('testItem');
        $attributes = $item
            ->addAttribute($attrName, $attrValueChanged)
            ->getAttributes();
        $this->assertArrayHasKey($attrName, $attributes);
        $this->assertNotContains($attrValue, $attributes);
        $this->assertContains($attrValueChanged, $attributes);
    }

    public function testGetMagicAttributes()
    {
        $item = new Formagic_Item_Mock_MockItem('testItem[]');
        $attributes = $item->getAttributes();

        // check for name in attributes
        $this->assertArrayHasKey('name', $attributes);
        $this->assertContains('testItem[]', $attributes);

        // check for id in attributes
        $this->assertArrayHasKey('id', $attributes);
        $this->assertContains('testItem', $attributes);

        // set name and id explicitly
        $item = new Formagic_Item_Mock_MockItem('testItem[]');
        $attributes = $item
            ->setAttributes(array('id' => 'testId', 'name' => 'testName'))
            ->getAttributes();

        $this->assertArrayHasKey('id', $attributes);
        $this->assertContains('testId', $attributes);

        $this->assertArrayHasKey('name', $attributes);
        $this->assertContains('testName', $attributes);

        // (re-)set attributes, voiding ID and name
        $attributes = $item->setAttributes(array('class' => 'testClass'))
            ->getAttributes();

        $this->assertArrayHasKey('id', $attributes);
        $this->assertContains('testItem', $attributes);

        $this->assertArrayHasKey('name', $attributes);
        $this->assertContains('testItem[]', $attributes);
    }

    /**
     * Tests that attributes string is correctly assembled
     */
    public function testGetAttributeStr()
    {
        $attrNameClass  = 'class';
        $attrValueClass = 'test';

        $attrNameName  = 'name';
        $attrValueName = 'item[key][]';

        $attrNameId  = 'id';
        $attrValueId = 'item-key';

        $item = new Formagic_Item_Mock_MockItem('item[key][]', array(
            'attributes' => array($attrNameClass => $attrValueClass)
        ));

        $attributeStr = $item->getAttributeStr();
        
        $this->assertSame(' '
            . $attrNameClass . '="' . $attrValueClass . '" '
            . $attrNameId . '="' . $attrValueId . '" '
            . $attrNameName  . '="' . $attrValueName . '"'
        , $attributeStr);
    }

    /**
     * Tests that rules are added correctly to the item
     */
    public function testAddRule()
    {
        // set by method and string
        $item = new Formagic_Item_Mock_MockItem('test');
        $res = $item->addRule('Mock_MockRule');
        $this->assertSame($item, $res);

        // set by method and object
        $item = new Formagic_Item_Mock_MockItem('test');
        $res = $item->addRule(new Formagic_Rule_Mock_MockRule());
        $this->assertSame($item, $res);
    }
    
    /**
     * Test that setting something other than string or rule object throws
     * an exception.
     * 
     * @expectedException Formagic_Exception
     */
    public function testAddRuleException()
    {
        $input  = new Formagic_Item_Mock_MockItem('test');
        $input->addRule(null);
    }


    public function testHasRule()
    {
        $ruleName = 'Mock_MockRule';
        $ruleName2 = 'Mock_MockRuleTrue';

        // set by option and string
        $item = new Formagic_Item_Mock_MockItem('test', array(
            'rules' => $ruleName
        ));
        $hasRule = $item->hasRule($ruleName);
        $this->assertTrue($hasRule);

        // set by option and object
        $item = new Formagic_Item_Mock_MockItem('test', array(
            'rules' => new Formagic_Rule_Mock_MockRule()
        ));
        $hasRule = $item->hasRule($ruleName);
        $this->assertTrue($hasRule);

        // set by option and string
        $item = new Formagic_Item_Mock_MockItem('test', array(
            'rules' => array($ruleName, $ruleName2)
        ));
        $hasRule = $item->hasRule($ruleName);
        $hasRule2 = $item->hasRule($ruleName2);
        $this->assertTrue($hasRule);
        $this->assertTrue($hasRule2);

        // set by option and object
        $item = new Formagic_Item_Mock_MockItem('test', array(
            'rules' => array(
                new Formagic_Rule_Mock_MockRule(),
                new Formagic_Rule_Mock_MockRuleTrue()
            )
        ));
        $hasRule = $item->hasRule($ruleName);
        $hasRule2 = $item->hasRule($ruleName2);
        $this->assertTrue($hasRule);
        $this->assertTrue($hasRule2);
    }

    /**
     * Tests that getRule() returns previously added rule
     */
    public function testGetRule()
    {
        $rule = new Formagic_Rule_Mock_MockRule();
        $input  = new Formagic_Item_Mock_MockItem('test', array('rules' => $rule));

        $actual = $input->getRule('Mock_MockRule');
        $this->assertSame($rule, $actual);
    }

    /**
     * Tests that getRule() throws exception if rule is not found
     *
     * @expectedException Formagic_Exception
     */
    public function testGetRuleException()
    {
        $input  = new Formagic_Item_Mock_MockItem('test');
        $input->getRule('RuleNotExists');
    }

    /**
     * Multiple tests on validate method
     */
    public function testValidate()
    {
        $item = new Formagic_Item_Mock_MockItem('test');

        // one rule, validates true
        $validationResult = $item
            ->setValue('1')
            ->addRule('Mock_MockRule')
            ->validate();
        $this->assertInternalType('boolean', $validationResult);
        $this->assertTrue($validationResult);

        // one rule, validates false
        $validationResult = $item
            ->setValue('0')
            ->addRule('Mock_MockRule')
            ->validate();
        $this->assertFalse($validationResult);

        // multiple rules
        $validationResult = $item
            ->setValue('1')
            ->addRule('Mock_MockRule')
            ->addRule('Mock_MockRuleTrue')
            ->validate();
        $this->assertTrue($validationResult);
    }

    /**
     * Tests that filtered values are validated
     */
    public function testValidateFilteredValue()
    {
        $item = new Formagic_Item_Mock_MockItem('test');
        $rule = $this->getMockForAbstractClass('Formagic_Rule_Abstract', array(), '', false);
        $filter = new Formagic_Filter_Replace(array('foo' => 'bar'));

        $rule
            ->expects($this->once())
            ->method('validate')
            ->with('bar')
            ->will($this->returnValue(true));

        // one rule, validates true
        $validationResult = $item
            ->setValue('foo')
            ->addRule($rule)
            ->addFilter($filter)
            ->validate();
        $this->assertTrue($validationResult);
    }

    /**
     * Tests that validating a value type that is no string or array throws
     * an exception
     * 
     * @expectedException Formagic_Exception
     */
    public function testValidateWrongTypeException()
    {
        $item = new Formagic_Item_Mock_MockItem('test');
        $item->setValue(new stdClass())
            ->addRule('Mock_MockRule')
            ->validate();
    }
    
    /**
     * Tests that violated rules are returned
     */
    public function testGetViolatedRules()
    {
        $item = new Formagic_Item_Mock_MockItem('test');
        $violatedRules = $item->getViolatedRules();
        $this->assertInternalType('array', $violatedRules);
        $this->assertEmpty($violatedRules);

        // one rule, validates false
        $rule = new Formagic_Rule_Mock_MockRule();
        $item
            ->setValue('0')
            ->addRule($rule)
            ->validate();

        $violatedRules = $item->getViolatedRules();
        $this->assertInternalType('array', $violatedRules);
        $this->assertNotEmpty($violatedRules);
        $this->assertContains($rule, $violatedRules);

        // one rule, validates false
        $rule2 = new Formagic_Rule_Mock_MockRuleFalse();
        $item
            ->setValue('0')
            ->addRule($rule2)
            ->validate();
        
        $violatedRules = $item->getViolatedRules();
        $this->assertNotEmpty($violatedRules);
        $this->assertContains($rule, $violatedRules);
        $this->assertContains($rule2, $violatedRules);
    }

    public function testSetReadonly()
    {
        // set readonly explicitly
        $item = new Formagic_Item_Mock_MockItem('test');
        $res = $item->setReadonly(true);
        $this->assertInstanceOf('Formagic_Item_Mock_MockItem', $res);

        $html = $item->getHtml();
        $this->assertEquals($html, Formagic_Item_Mock_MockItem::HTML_OUTPUT_READONLY);

        $item->setReadonly(false);
        $html = $item->getHtml();
        $this->assertEquals($html, Formagic_Item_Mock_MockItem::HTML_OUTPUT);

        // set readonly by option
        $item = new Formagic_Item_Mock_MockItem('test', array('readonly' => true));
        $html = $item->getHtml();
        $this->assertEquals($html, Formagic_Item_Mock_MockItem::HTML_OUTPUT_READONLY);

        $item = new Formagic_Item_Mock_MockItem('test', array('readonly' => false));
        $html = $item->getHtml();
        $this->assertEquals($html, Formagic_Item_Mock_MockItem::HTML_OUTPUT);
    }

    public function testSetHidden()
    {
        $item = new Formagic_Item_Mock_MockItem('test');
        
        // test default
        $isHidden = $item->isHidden();
        $this->assertFalse($isHidden);

        // set explicitly
        $res = $item->setHidden(true);
        $this->assertInstanceOf('Formagic_Item_Mock_MockItem', $res);

        $isHidden = $item->isHidden();
        $this->assertTrue($isHidden);

        // set by option
        $item = new Formagic_Item_Mock_MockItem('test', array('hidden' => true));
        $isHidden = $item->isHidden();
        $this->assertTrue($isHidden);

        $item = new Formagic_Item_Mock_MockItem('test', array('hidden' => false));
        $isHidden = $item->isHidden();
        $this->assertFalse($isHidden);
    }

    public function testSetIgnore()
    {
        $item = new Formagic_Item_Mock_MockItem('test');

        // test default
        $isIgnored = $item->isIgnored();
        $this->assertFalse($isIgnored);

        // set explicitly
        $res = $item->setIgnore(true);
        $this->assertInstanceOf('Formagic_Item_Mock_MockItem', $res);

        $isIgnored = $item->isIgnored();
        $this->assertTrue($isIgnored);

        // set by option
        $item = new Formagic_Item_Mock_MockItem('test', array('ignore' => true));
        $isIgnored = $item->isIgnored();
        $this->assertTrue($isIgnored);

        $item = new Formagic_Item_Mock_MockItem('test', array('ignore' => false));
        $isIgnored = $item->isIgnored();
        $this->assertFalse($isIgnored);
    }

    public function testSetDisabled()
    {
        $item = new Formagic_Item_Mock_MockItem('test');

        // test default
        $isDisabled = $item->isDisabled();
        $this->assertFalse($isDisabled);

        // set explicitly
        $res = $item->setDisabled(true);
        $this->assertInstanceOf('Formagic_Item_Mock_MockItem', $res);

        $isDisabled = $item->isDisabled();
        $this->assertTrue($isDisabled);

        // set by option
        $item = new Formagic_Item_Mock_MockItem('test', array('disable' => true));
        $isDisabled = $item->isDisabled();
        $this->assertTrue($isDisabled);

        $item = new Formagic_Item_Mock_MockItem('test', array('disable' => false));
        $isDisabled = $item->isDisabled();
        $this->assertFalse($isDisabled);
    }

    public function testSetFixed()
    {
        $expected = 'fix';
        // set setting option by method
        $item = new Formagic_Item_Mock_MockItem('test', array(
            'value' => $expected
        ));
        $res = $item->setFixed(true);
        $this->assertSame($res, $item);
        
        $item->setValue('newVal');
        $actual = $item->getValue();
        $this->assertEquals($expected, $actual);
        
        // test setting property by option
        $item = new Formagic_Item_Mock_MockItem('test', array(
            'fixed' => true,
            'value' => $expected
        ));
        $item->setValue('newVal');
        $actual = $item->getValue();
        $this->assertEquals($expected, $actual);
        
        // test setting back fixed flag to false
        $expected = 'newVal';
        $item->setFixed(false);
        $item->setValue($expected);
        $actual = $item->getValue();
        $this->assertEquals($expected, $actual);
    }

    public function testGetType()
    {
        $expected = 'undefined';
        /** @var Formagic_Item_Abstract $subject */
        $subject = $this->getMockForAbstractClass('Formagic_Item_Abstract', array('abstract'));
        $actual = $subject->getType();
        $this->assertEquals($expected, $actual);
    }
}
