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
 * Tests Formagic ItemDependency rule
 *
 * @package     Formagic\Tests
 * @author      Florian Sonnenburg
 **/
class Formagic_Rule_ItemDependency_Test extends Formagic_Rule_TestCase
{
    /**
     * Test case setup
     */
    public function setUp()
    {
    }
    
    /**
     * Tests that setting no dependecy item throws an exception
     * 
     * @expectedException Formagic_Exception
     */
    public function testSettingNoItemException()
    {
        $rule = new Formagic_Rule_ItemDependency(array(
            'condition' => Formagic_Rule_ItemDependency::COND_VALUE_EQUALS,
            'requirement' => 1
        ));
    }
    
    /**
     * Test that setting wrong item option throws an exception
     * 
     * @expectedException Formagic_Exception
     */
    public function testSettingWrongItemException()
    {
        $rule = new Formagic_Rule_ItemDependency(array(
            'item' => 'noItem',
            'condition' => Formagic_Rule_ItemDependency::COND_VALUE_EQUALS,
            'requirement' => 1
        ));
    }
    
    /**
     * Tests that setting no dependency condition throws an exception
     * 
     * @expectedException Formagic_Exception
     */
    public function testSettingNoConditionException()
    {
        $this->_mockItem = $this->_getMockItem('irrelevant');
        $rule = new Formagic_Rule_ItemDependency(array(
            'item' => $this->_mockItem,
            'requirement' => 1
        ));
    }
    
    /**
     * Tests that setting a wrong dependency condition throws an exception
     * 
     * @expectedException Formagic_Exception
     */
    public function testSettingWrongConditionException()
    {
        $this->_mockItem = $this->_getMockItem('irrelevant');
        $rule = new Formagic_Rule_ItemDependency(array(
            'item' => $this->_mockItem,
            'condition' => 'invalidCondition',
            'requirement' => 1
        ));
    }
    
    /**
     * Tests that setting no dependecy value throws an exception
     * 
     * @expectedException Formagic_Exception
     */
    public function testSettingNoDependencyException()
    {
        $this->_mockItem = $this->_getMockItem('irrelevant');
        $rule = new Formagic_Rule_ItemDependency(array(
            'item' => $this->_mockItem,
            'condition' => Formagic_Rule_ItemDependency::COND_VALUE_EQUALS,
        ));

    }

    /**
     * Tests validates false if dependent item has any value
     */
    public function testValidateFalseForAnyCompareValue()
    {
        $compareMockItem = $this->_getMockItem('anyOther');
        $rule = new Formagic_Rule_ItemDependency(array(
            'item' => $compareMockItem,
            'condition' => Formagic_Rule_ItemDependency::COND_VALUE_EQUALS,
            'requirement' => Formagic_Rule_ItemDependency::VALUE_ANY
        ));
        $actual = $rule->validate('irrelevantValue');
        $this->assertFalse($actual);
    }
    
    /**
     * Proof of the contrary to testValidateFalseForAnyCompareValue
     */
    public function testValidateTrueForAnyCompareValue()
    {
        $compareMockItem = $this->_getMockItem(null);
        $rule = new Formagic_Rule_ItemDependency(array(
            'item' => $compareMockItem,
            'condition' => Formagic_Rule_ItemDependency::COND_VALUE_EQUALS,
            'requirement' => Formagic_Rule_ItemDependency::VALUE_ANY
        ));
        $actual = $rule->validate('irrelevantValue');
        $this->assertTrue($actual);
    }
    
    /**
     * Tests validates false if dependent item has no value
     */
    public function testValidateFalseForNoCompareValue()
    {
        $compareMockItem = $this->_getMockItem(null);
        $rule = new Formagic_Rule_ItemDependency(array(
            'item' => $compareMockItem,
            'condition' => Formagic_Rule_ItemDependency::COND_VALUE_EQUALS,
            'requirement' => Formagic_Rule_ItemDependency::VALUE_NONE
        ));
        $actual = $rule->validate('irrelevantValue');
        $this->assertFalse($actual);
    }
    
    /**
     * Proof of the contrary to testValidateFalseForNoCompareValue
     */
    public function testValidateTrueForNoCompareValue()
    {
        $compareMockItem = $this->_getMockItem('any');
        $rule = new Formagic_Rule_ItemDependency(array(
            'item' => $compareMockItem,
            'condition' => Formagic_Rule_ItemDependency::COND_VALUE_EQUALS,
            'requirement' => Formagic_Rule_ItemDependency::VALUE_NONE
        ));
        $actual = $rule->validate('irrelevantValue');
        $this->assertTrue($actual);
    }
    
    /**
     * Tests validation false for a specific dependent item value
     */
    public function testValidateFalseForSpecificValue()
    {
        $dependencyValue = 'value';
        $compareMockItem = $this->_getMockItem($dependencyValue);
        $rule = new Formagic_Rule_ItemDependency(array(
            'item' => $compareMockItem,
            'condition' => Formagic_Rule_ItemDependency::COND_VALUE_EQUALS,
            'requirement' => $dependencyValue
        ));
        $actual = $rule->validate('irrelevantValue');
        $this->assertFalse($actual);
    }
    
    /**
     * Proof of the contrary to testValidateFalseForSpecificValue
     */
    public function testValidateTrueForSpecificValue()
    {
        $compareMockItem = $this->_getMockItem('value');
        $rule = new Formagic_Rule_ItemDependency(array(
            'item' => $compareMockItem,
            'condition' => Formagic_Rule_ItemDependency::COND_VALUE_EQUALS,
            'requirement' => 'otherValue'
        ));
        $actual = $rule->validate('irrelevantValue');
        $this->assertTrue($actual);
    }
    
    /**
     * Tests validation false for dependent item not having any value
     */
    public function testValidateFalseNotHavingAnyCompareValue()
    {
        $compareMockItem = $this->_getMockItem(null);
        $rule = new Formagic_Rule_ItemDependency(array(
            'item' => $compareMockItem,
            'condition' => Formagic_Rule_ItemDependency::COND_VALUE_NOT_EQUALS,
            'requirement' => Formagic_Rule_ItemDependency::VALUE_ANY
        ));
        $actual = $rule->validate('irrelevantValue');
        $this->assertFalse($actual);
    }
    
    /**
     * Proof of the contrary to testValidateFalseNotHavingAnyCompareValue
     */
    public function testValidateTrueNotHavingAnyCompareValue()
    {
        $compareMockItem = $this->_getMockItem('any');
        $rule = new Formagic_Rule_ItemDependency(array(
            'item' => $compareMockItem,
            'condition' => Formagic_Rule_ItemDependency::COND_VALUE_NOT_EQUALS,
            'requirement' => Formagic_Rule_ItemDependency::VALUE_ANY
        ));
        $actual = $rule->validate('irrelevantValue');
        $this->assertTrue($actual);
    }
    
    /**
     * Tests validation false for dependent item not having no value 
     */
    public function testValidateFalseNotHavingNoCompareValue()
    {
        $compareMockItem = $this->_getMockItem('any');
        $rule = new Formagic_Rule_ItemDependency(array(
            'item' => $compareMockItem,
            'condition' => Formagic_Rule_ItemDependency::COND_VALUE_NOT_EQUALS,
            'requirement' => Formagic_Rule_ItemDependency::VALUE_NONE
        ));
        $actual = $rule->validate('irrelevantValue');
        $this->assertFalse($actual);
    }
    
    /**
     * Proof of the contrary to testValidateFalseNotHavingNoCompareValue
     */
    public function testValidateTrueNotHavingNoCompareValue()
    {
        $compareMockItem = $this->_getMockItem(null);
        $rule = new Formagic_Rule_ItemDependency(array(
            'item' => $compareMockItem,
            'condition' => Formagic_Rule_ItemDependency::COND_VALUE_NOT_EQUALS,
            'requirement' => Formagic_Rule_ItemDependency::VALUE_NONE
        ));
        $actual = $rule->validate('irrelevantValue');
        $this->assertTrue($actual);
    }
    
    /**
     * Tests validation false for dependend item not having one specific value
     */
    public function testValidateFalseNotHavingSpecificValue()
    {
        $dependencyValue = 'value';
        $compareMockItem = $this->_getMockItem('otherValue');
        $rule = new Formagic_Rule_ItemDependency(array(
            'item' => $compareMockItem,
            'condition' => Formagic_Rule_ItemDependency::COND_VALUE_NOT_EQUALS,
            'requirement' => $dependencyValue
        ));
        $actual = $rule->validate('irrelevantValue');
        $this->assertFalse($actual);
    }
    
    /**
     * Proof of the contrary to testValidateFalseNotHavingSpecificValue
     */
    public function testValidateTrueNotHavingSpecificValue()
    {
        $dependencyValue = 'value';
        $compareMockItem = $this->_getMockItem('value');
        $rule = new Formagic_Rule_ItemDependency(array(
            'item' => $compareMockItem,
            'condition' => Formagic_Rule_ItemDependency::COND_VALUE_NOT_EQUALS,
            'requirement' => $dependencyValue
        ));
        $actual = $rule->validate('irrelevantValue');
        $this->assertTrue($actual);
    }
}
