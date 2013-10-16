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
 * @package     Test
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2007-2013 Florian Sonnenburg
 * @license     http://www.formagic-php.net/license-agreement/   New BSD License
 */

/**
 * Tests Formagic equal rule
 *
 * @category    Formagic
 * @package     Tests
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2011 Florian Sonnenburg
 **/
class Formagic_Rule_Equal_Test extends Formagic_Rule_TestCase
{
    /**
     * @expectedException Formagic_Exception
     */
    public function testSettingWrongCompareItemException()
    {
        new Formagic_Rule_Equal(array('item' => 'test'));
    }

    /**
     * @expectedException Formagic_Exception
     */
    public function testSettingNoCompareItemException()
    {
        new Formagic_Rule_Equal();
    }

    /**
     * Test that two items returning identical value validate to true
     */
    public function testValidateTrue()
    {
        $compareValue = 'testValue';
        $mockCompareItem = $this->_getMockItem($compareValue);
        $rule = new Formagic_Rule_Equal(array('item' => $mockCompareItem));
        $actual = $rule->validate($compareValue);
        $this->assertTrue($actual);
    }
    /**
     * Test that two items returning different value validate to false
     */
    public function testValidateFalse()
    {
        $mockCompareItem = $this->_getMockItem('otherValue');
        $rule = new Formagic_Rule_Equal(array('item' => $mockCompareItem));
        $actual = $rule->validate('testValue');
        $this->assertFalse($actual);
    }
}