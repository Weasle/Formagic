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
 * Tests Formagic regex rule
 *
 * @package     Formagic\Tests
 * @author      Florian Sonnenburg
 **/
class Formagic_Rule_Regex_Test extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException Formagic_Exception
     */
    public function testSettingNoPattern()
    {
        $rule = new Formagic_Rule_Regex();
    }

    /**
     * Test that rule validates to true if no value is set
     */
    public function testValidateNoValue()
    {
        $rule = new Formagic_Rule_Regex(array(
            'pattern' => '/^test$/'
        ));
        $actual = $rule->validate(null);
        $this->assertTrue($actual);   
    }
    
    /**
     * Test that regex pattern is validated correctly
     */
    public function testValidateTrue()
    {
        $rule = new Formagic_Rule_Regex(array(
            'pattern' => '/^test$/'
        ));
        $actual = $rule->validate('test');
        $this->assertTrue($actual);
    }
    /**
     * Test that two items returning different value validate to false
     */
    public function testValidateFalse()
    {
        $rule = new Formagic_Rule_Regex(array(
            'pattern' => '/^noMatch$/'
        ));
        $actual = $rule->validate('test');
        $this->assertFalse($actual);
    }
}