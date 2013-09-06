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
 * Load mock rule class
 */
require_once realpath(dirname(__FILE__) . '/../MockClasses/Rule/MockRule.php');

/**
 * Tests Formagic rule interface
 *
 * @category    Formagic
 * @package     Tests
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2010
 * @version     $Id: AbstractTest.php 173 2012-05-16 13:19:22Z meweasle $
 **/
class Formagic_Rule_Abstract_Test extends PHPUnit_Framework_TestCase
{
    /**
     * Test fluent interface for setMessage()
     */
    public function testSetMessage()
    {
        $rule = new Formagic_Rule_MockRule();
        $actual = $rule->setMessage('test');
        $this->assertSame($rule, $actual);
    }
    
    /**
     * Test fluent interface for setMessage()
     */
    public function testGetMessage()
    {
        $rule = new Formagic_Rule_MockRule();
        $expected = 'test';
        $actual = $rule->setMessage($expected)->getMessage();
        $this->assertSame($expected, $actual);
    }
    
    /**
     * Tests that message can be set by option
     */
    public function testSetMesageByOption()
    {
        $expected = 'test';
        $rule = new Formagic_Rule_MockRule(array(
            'message' => $expected
        ));
        $actual = $rule->getMessage();
        $this->assertSame($expected, $actual);
    }
    
    /**
     * Test that rule name is returned
     */
    public function testGetName()
    {
        $rule = new Formagic_Rule_MockRule();
        $actual = $rule->getName();
        $this->assertEquals('MockRule', $actual);
    }
}
