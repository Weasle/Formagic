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
 * Tests Formagic numeric rule
 *
 * @category    Formagic
 * @package     Tests
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2011 Florian Sonnenburg
 **/
class Formagic_Rule_Numeric_Test extends PHPUnit_Framework_TestCase
{
    /**
     * Rule object
     * @var Formagic_Rule_Numeric
     */
    protected $_rule;

    /**
     * Setup test case
     */
    public function setUp()
    {
        $this->_rule = new Formagic_Rule_Numeric();
    }

    /**
     * Test that rule validates to true if no value is set
     */
    public function testValidateNoValue()
    {
        $actual = $this->_rule->validate(null);
        $this->assertTrue($actual);

        $actual = $this->_rule->validate('');
        $this->assertTrue($actual);
    }

    /**
     * Test that rule validates 0 true
     */
    public function testValidateZero()
    {
        $actual = $this->_rule->validate(0);
        $this->assertTrue($actual);
    }

    /**
     * Test that rule validates true for a numeric value
     */
    public function testValidateTrue()
    {
        $actual = $this->_rule->validate('5');
        $this->assertTrue($actual);
    }

    /**
     * Test that rule validates false for non-numeric value
     */
    public function testValidateFalse()
    {
        $actual = $this->_rule->validate('n/n');
        $this->assertFalse($actual);
    }

    /**
     * Test that string is invalid even if it starts with a numeric value
     */
    public function testValidateStringContainingNumericFalse()
    {
        $actual = $this->_rule->validate('100 Meters');
        $this->assertFalse($actual);
    }

    /**
     * Test that floats are valid
     */
    public function testValidateFloats()
    {
        $actual = $this->_rule->validate('1.001');
        $this->assertTrue($actual);
    }

    /**
     * Test that float values containing colons as decimal separators are valid
     */
    public function testValidateGermanFloats()
    {
        $actual = $this->_rule->validate('100,99');
        $this->assertTrue($actual);
    }
}
