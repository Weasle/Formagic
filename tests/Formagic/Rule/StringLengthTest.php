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
 * @version     $Id: StringLengthTest.php 173 2012-05-16 13:19:22Z meweasle $
 **/
class Formagic_Rule_StringLength_Test extends PHPUnit_Framework_TestCase
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
     * Test valid minimal boundary validation
     */
    public function testValidateMinTrue()
    {
        $rule = new Formagic_Rule_StringLength(array('min' => 10));
        $actual = $rule->validate('1234567890');
        $this->assertTrue($actual);
    }
    
    /**
     * Test invalid minimal boundary validation
     */
    public function testValidateMinFalse()
    {
        $rule = new Formagic_Rule_StringLength(array('min' => 100));
        $actual = $rule->validate('1234567890');
        $this->assertFalse($actual);
    }
    
    /**
     * Test valid maximal boundary validation
     */
    public function testValidateMaxTrue()
    {
        $stringValue = '1234567890';
        $rule = new Formagic_Rule_StringLength(array('max' => 10));
        $actual = $rule->validate($stringValue);
        $this->assertTrue($actual);
    }
    
    /**
     * Test valid between boundary validation
     */
    public function testValidateBetweenTrue()
    {
        $rule = new Formagic_Rule_StringLength(array('min' => 0, 'max' => 10));
        $actual = $rule->validate('12345');
        $this->assertTrue($actual);
    }
    
    /**
     * Test invalid between boundary validation
     */
    public function testValidateBetweenFalse()
    {
        $rule = new Formagic_Rule_StringLength(array('min' => 0, 'max' => 5));
        $actual = $rule->validate('123456');
        $this->assertFalse($actual);
    }
    

    
    /**
     * Test invalid maximal boundary validation
     */
    public function testValidateMaxFalse()
    {
        $stringValue = '1234567890';
        $rule = new Formagic_Rule_StringLength(array('max' => 1));
        $actual = $rule->validate($stringValue);
        $this->assertFalse($actual);
    }
    
    /**
     * Test that rule validates true if no value at all is set (null value)
     */
    public function testValidateTrueForNoValue()
    {
        $rule = new Formagic_Rule_StringLength(array('min' => 0));
        $validationResult = $rule->validate(null);
        $this->assertTrue($validationResult);
    }
}
