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
 * Tests Formagic SessionValue rule
 *
 * @category    Formagic
 * @package     Tests
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2012 Florian Sonnenburg
 **/
class Formagic_Rule_SessionValue_Test extends PHPUnit_Framework_TestCase
{
    /**
     * Rule object
     * @var Formagic_Rule_SessionValue
     */
    protected $_rule;

    /**
     * Session mock
     * @var Formagic_Session_Interface
     */
    protected $_sessionMock;

    /**
     * Setup test case
     */
    public function setUp()
    {
        $this->_sessionMock = $this
            ->getMockBuilder('Formagic_Session_Interface')
            ->getMock();
    }

    /**
     * Test that rule validates to true if no value is set
     */
    public function testValidateNoValue()
    {
        $rule = $this->_getSubject('test');
        $actual = $rule->validate(null);
        $this->assertTrue($actual);

        $actual = $rule->validate('');
        $this->assertTrue($actual);
    }

    /**
     * Test that rule validates 0 true
     */
    public function testValidateZero()
    {
        $rule = $this->_getSubject('test');
        $actual = $rule->validate(0);
        $this->assertFalse($actual);
    }

    /**
     * Test that rule validates true for same value / session value
     */
    public function testValidateTrue()
    {
        $expected = 'value';
        $this->_sessionMock
            ->expects($this->once())
            ->method('get')
            ->with('test')
            ->will($this->returnValue($expected));
        $this->_sessionMock
            ->expects($this->once())
            ->method('has')
            ->with('test')
            ->will($this->returnValue(true));
        $rule = $this->_getSubject('test');
        $actual = $rule->validate($expected);
        $this->assertTrue($actual);
    }

    /**
     * Test that rule validates true for same value / session value
     */
    public function testSessionKeyDoesNotExist()
    {
        $sessionKey = 'n/a';
        $expected = 'value';
        $this->_sessionMock
            ->expects($this->never())
            ->method('get');
        $this->_sessionMock
            ->expects($this->once())
            ->method('has')
            ->with($sessionKey)
            ->will($this->returnValue(false));
        $rule = $this->_getSubject($sessionKey);
        $actual = $rule->validate($expected);
        $this->assertFalse($actual);
    }

    public function testSessionKeyValueDoesNotMatch()
    {
        $sessionKey = 'yes';
        $this->_sessionMock
            ->expects($this->once())
            ->method('get')
            ->with($sessionKey)
            ->will($this->returnValue('somethingElse'));
        $this->_sessionMock
            ->expects($this->once())
            ->method('has')
            ->with($sessionKey)
            ->will($this->returnValue(true));
        $rule = $this->_getSubject($sessionKey);
        $actual = $rule->validate('something');
        $this->assertFalse($actual);
    }

    /**
     * @expectedException Formagic_Exception
     */
    public function testNoSessionObject()
    {
        new Formagic_Rule_SessionValue(array());
    }

    /**
     * @expectedException Formagic_Exception
     */
    public function testInvalidSessionObject()
    {
        new Formagic_Rule_SessionValue(array('session' => 'noSessionObject'));
    }

    /**
     * @expectedException Formagic_Exception
     */
    public function testNoSessionKey()
    {
        new Formagic_Rule_SessionValue(array('session' => $this->_sessionMock));
    }

    /**
     *
     * @param string $sessionKey
     * @return \Formagic_Rule_SessionValue
     */
    protected function _getSubject($sessionKey)
    {
        $rule = new Formagic_Rule_SessionValue(
            array(
                'session' => $this->_sessionMock,
                'sessionKey' => $sessionKey
            )
        );
        return $rule;
    }
}
