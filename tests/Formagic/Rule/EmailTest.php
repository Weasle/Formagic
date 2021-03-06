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
 * Tests Formagic mandatory rule
 *
 * @package     Formagic\Tests
 * @author      Florian Sonnenburg
 **/
class Formagic_Rule_Email_Test extends PHPUnit_Framework_TestCase
{
    /**
     * Tests that validation strategy can be set by option without exception
     */
    public function testSelectValidationStrategy()
    {
        $mockStrategy = $this->getMock('Formagic_Rule_EmailValidation_Interface');
        $rule = new Formagic_Rule_Email(array(
            'validationStrategy' => $mockStrategy
        ));
        $this->assertInstanceOf('Formagic_Rule_Email', $rule);
    }

    /**
     * Tests that setting wrong validation strategy throws exception
     *
     * @expectedException Formagic_Exception
     */
    public function testSelectValidationStrategyException()
    {
        new Formagic_Rule_Email(array('validationStrategy' => 'na'));
    }

    /**
     * Tests that rule validates true if value is not set
     */
    public function testValidateNoValue()
    {
        $rule = new Formagic_Rule_Email();
        $actual = $rule->validate(null);
        $this->assertTrue($actual);

        $actual = $rule->validate('');
        $this->assertTrue($actual);
    }

    /**
     * Tests that rule validates 0 as a user value
     */
    public function testValidateZero()
    {
        $rule = new Formagic_Rule_Email();
        $actual = $rule->validate(0);
        $this->assertFalse($actual);
    }

    /**
     * Tests that rule validate false if value is no valid mail address
     */
    public function testValidateFalse()
    {
        $rule = new Formagic_Rule_Email();
        $actual = $rule->validate('nomailaddress');
        $this->assertFalse($actual);
    }

    /**
     * Tests that rule validates true for valid mail addresses
     */
    public function testValidateTrue()
    {
        $rule = new Formagic_Rule_Email();
        $actual = $rule->validate('example@example.com');
        $this->assertTrue($actual);
    }

    /**
     * Tests that rule validates false for formally valid mail address with
     * invalid domain part
     */
    public function testValidateFalseWithDnsCheck()
    {
        $rule = new Formagic_Rule_Email(array('checkDns' => true));
        $actual = $rule->validate('noMail@nonExistingSld.nonExistingTld');
        $this->assertFalse($actual);
    }

    /**
     * Tests that rule validates true for valid mail address with valid
     * domain part
     */
    public function testValidateTrueWithDnsCheck()
    {
        $rule = new Formagic_Rule_Email(array('checkDns' => true));
        $actual = $rule->validate('example@example.com');
        $this->assertTrue($actual);
    }
}
