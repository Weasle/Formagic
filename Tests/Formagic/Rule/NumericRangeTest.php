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
 * @copyright   Copyright (c) 2007-2011 Florian Sonnenburg
 * @license     http://www.formagic-php.net/license-agreement/   New BSD License
 */

/**
 * Tests Formagic equal rule
 *
 * @category    Formagic
 * @package     Tests
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2007-2011 Florian Sonnenburg
 * @version     $Id: NumericRangeTest.php 173 2012-05-16 13:19:22Z meweasle $
 **/
class Formagic_Rule_NumericRange_Test extends PHPUnit_Framework_TestCase
{
    protected $_messages;
    
    const MIN_MESSAGE = 'min %s';
    const MAX_MESSAGE = 'max %s';
    const BETWEEN_MESSAGE = 'between %s';
    
    /**
     * Initialize test
     */
    public function setUp() {
        $this->_messages = array(
            'min' => self::MIN_MESSAGE,
            'max' => self::MAX_MESSAGE,
            'between' => self::BETWEEN_MESSAGE
        );
    }

    /**
     * Test that an exception is thrown if no options are given
     * 
     * @expectedException Formagic_Exception
     */
    public function testSettingNoRangeException()
    {
        $rule = new Formagic_Rule_NumericRange();
    }
    
    /**
     * Test that an exception is thrown if minimal boundary is invalid
     * 
     * @expectedException Formagic_Exception
     */
    public function testSettingInvalidMinRangeException()
    {
        $rule = new Formagic_Rule_NumericRange(array('min' => 'string'));
    }
    
    /**
     * Test that an exception is thrown if maximal boundary is invalid
     * 
     * @expectedException Formagic_Exception
     */
    public function testSettingInvalidMaxRangeException()
    {
        $rule = new Formagic_Rule_NumericRange(array('max' => 'string'));
    }
    
    /**
     * Test that setting either min or max boundary is valid
     */
    public function testSettingOnlyOneBorder()
    {
        $rule1 = new Formagic_Rule_NumericRange(array('min' => 1));
        $this->assertInstanceOf('Formagic_Rule_NumericRange', $rule1);
        $rule2 = new Formagic_Rule_NumericRange(array('max' => 1));
        $this->assertInstanceOf('Formagic_Rule_NumericRange', $rule2);
    }

    /**
     * Test valid minimal boundary validation
     */
    public function testValidateMinTrue()
    {
        $stringValue = '10';
        $rule = new Formagic_Rule_NumericRange(array('min' => 10));
        $actual = $rule->validate($stringValue);
        $this->assertTrue($actual);
    }
    
    /**
     * Test valid minimal boundary validation using 0
     */
    public function testValidateMinZeroTrue()
    {
        $stringValue = '0';
        $rule = new Formagic_Rule_NumericRange(array('min' => 0));
        $actual = $rule->validate($stringValue);
        $this->assertTrue($actual);
    }
    
    /**
     * Test valid minimal boundary validation using negative boundary
     */
    public function testValidateMinNegativeTrue()
    {
        $stringValue = '0';
        $rule = new Formagic_Rule_NumericRange(array('min' => -10));
        $actual = $rule->validate($stringValue);
        $this->assertTrue($actual);
    }
    
    /**
     * Test invalid minimal boundary validation
     */
    public function testValidateMinFalse()
    {
        $stringValue = '10';
        $rule = new Formagic_Rule_NumericRange(array('min' => 11));
        $actual = $rule->validate($stringValue);
        $this->assertFalse($actual);
    }
    
    /**
     * Test valid maximal boundary validation
     */
    public function testValidateMaxTrue()
    {
        $stringValue = '10';
        $rule = new Formagic_Rule_NumericRange(array('max' => 10));
        $actual = $rule->validate($stringValue);
        $this->assertTrue($actual);
    }
    
    /**
     * Test valid maximal boundary validation using 0
     */
    public function testValidateMaxZeroTrue()
    {
        $stringValue = '-1';
        $rule = new Formagic_Rule_NumericRange(array('max' => 0));
        $actual = $rule->validate($stringValue);
        $this->assertTrue($actual);
    }
    
    /**
     * Test valid maximal boundary validation using negative value
     */
    public function testValidateMaxNegativeTrue()
    {
        $stringValue = '-10';
        $rule = new Formagic_Rule_NumericRange(array('max' => -1));
        $actual = $rule->validate($stringValue);
        $this->assertTrue($actual);
    }
    
    /**
     * Test invalid maximal boundary validation
     */
    public function testValidateMaxFalse()
    {
        $stringValue = '10';
        $rule = new Formagic_Rule_NumericRange(array('max' => 9));
        $actual = $rule->validate($stringValue);
        $this->assertFalse($actual);
    }
    
    /**
     * Test valid between boundary validation
     */
    public function testValidateBetweenTrue()
    {
        $rule = new Formagic_Rule_NumericRange(array('min' => 0, 'max' => 10));
        $actual = $rule->validate('5');
        $this->assertTrue($actual);
    }
    
    /**
     * Test invalid between boundary validation
     */
    public function testValidateBetweenFalse()
    {
        $rule = new Formagic_Rule_NumericRange(array('min' => -10, 'max' => 10));
        $actual = $rule->validate('-50');
        $this->assertFalse($actual);
    }
    
    /**
     * Test that setMessages() provides fluent interface
     */
    public function testSetMessagesResult()
    {
        $rule = new Formagic_Rule_NumericRange(array('min' => 0));
        $actual = $rule->setMessages($this->_messages);
        $this->assertInstanceOf('Formagic_Rule_NumericRange', $actual);
    }
    
    /**
     * Test that setMessages() only accepts array with sufficient information
     * 
     * @expectedException Formagic_Exception
     */
    public function testSetMessagesNoneException()
    {
        $rule = new Formagic_Rule_NumericRange(array('min' => 0));
        $actual = $rule->setMessages(array());
    }
    
    /**
     * Test that setMessages() accepts array containing more keys than necessary
     */
    public function testSetTooManyMessages()
    {
        $rule = new Formagic_Rule_NumericRange(array('min' => 0));
        $actual = $rule->setMessages(array(
            'min' => '',
            'max' => '',
            'between' => '',
            'more' => ''
        ));
    }
    
    /**
     * Test that minimal message is set correctly
     */
    public function testValidateMinFalseMessage()
    {
        $minBorder = 11;
        $rule = new Formagic_Rule_NumericRange(array(
            'min' => $minBorder,
            'messages' => $this->_messages
        ));
        $validationResult = $rule->validate('10');
        $this->assertFalse($validationResult);
        $actual = $rule->getMessage();
        $expected = sprintf(self::MIN_MESSAGE, $minBorder);
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Test that maximal message is set correctly
     */
    public function testValidateMaxFalseMessage()
    {
        $maxBorder = 10;
        $rule = new Formagic_Rule_NumericRange(array(
            'max' => $maxBorder,
            'messages' => $this->_messages
        ));
        $validationResult = $rule->validate('100');
        $this->assertFalse($validationResult);
        $actual = $rule->getMessage();
        $expected = sprintf(self::MAX_MESSAGE, $maxBorder);
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Test that between message is set correctly
     */
    public function testValidateBetweenFalseMessage()
    {
        $minBorder = 1;
        $maxBorder = 10;
        $rule = new Formagic_Rule_NumericRange(array(
            'min' => $minBorder,
            'max' => $maxBorder,
            'messages' => $this->_messages
        ));
        $validationResult = $rule->validate('50');
        $this->assertFalse($validationResult);
        $actual = $rule->getMessage();
        $expected = sprintf(self::BETWEEN_MESSAGE, $minBorder, $maxBorder);
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Test that setMessage() is disabled
     * 
     * @expectedException Formagic_Exception
     */
    public function testSetMessageException()
    {
        $rule = new Formagic_Rule_NumericRange(array('min' => 0));
        $rule->setMessage('');
    }
    
    /**
     * Test that rule validates true if no value at all is set (null value)
     */
    public function testValidateTrueForNoValue()
    {
        $rule = new Formagic_Rule_NumericRange(array('min' => 0));
        $validationResult = $rule->validate(null);
        $this->assertTrue($validationResult);
    }
}
