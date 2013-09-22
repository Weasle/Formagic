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
 * @copyright   Copyright (c) 2007-2012 Florian Sonnenburg
 * @license     http://www.formagic-php.net/license-agreement/   New BSD License
 */

/**
 * Tests Formagic public interface
 *
 * Translation tests are separated into own testcase, see
 * {@see Formagic_Translator_Test}.
 *
 * @category    Formagic
 * @package     Tests
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2012
 * @version     $Id: PhpTest.php 173 2012-05-16 13:19:22Z meweasle $
 **/
class Formagic_Session_Php_Test extends PHPUnit_Framework_TestCase
{
    /**
     * Test subject
     * @var Formagic_Session_Php
     */
    private $_subject;

    /**
     * Initalization
     *
     * @return void
     */
    public function setUp()
    {
        $_SESSION = array('test' => array());
        $this->_subject = new Formagic_Session_Php('test');
    }

    /**
     * Tests that set() implements fluent interface
     */
    public function testSet()
    {
        $actual = $this->_subject->set('anyKey', 'anyValue');
        $this->assertSame($this->_subject, $actual);
    }

    /**
     * Tests that getter returns the formery set value
     */
    public function testSetGet()
    {
        $expected = 'value';
        $key      = 'key';
        $this->_subject->set($key, $expected);
        $actual = $this->_subject->get($key);
        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests that test subject handles uninitialized sessions gracefully
     */
    public function testGetOnUninitializedSessionNamespace()
    {
        $_SESSION = array();
        $subject = new Formagic_Session_Php('test');

        $actual = $subject->has('notExists');
        $this->assertFalse($actual);
    }

    /**
     * Tests that getter returns the exisiting value stored in session
     */
    public function testGetOnExistingSessionNamespace()
    {
        $namespace = 'test';
        $expected  = 'value';
        $key       = 'key';

        $_SESSION = array($namespace => array($key => $expected));
        $subject = new Formagic_Session_Php($namespace);

        $actual = $subject->get($key);
        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests that an exception is thrown when trying to get a non-existing value
     *
     * @expectedException Formagic_Exception_SessionException
     */
    public function testGetInvalid()
    {
        $this->_subject->get('NotExists');
    }

    /**
     * Tests that has() returns true when called on existing values
     */
    public function testHasTrue()
    {
        $key      = 'key';
        $this->_subject->set($key, 'anyValue');
        $actual = $this->_subject->has($key);
        $this->assertTrue($actual);
    }

    /**
     * Tests that has() returns false when called on non-existing value
     */
    public function testHasFalse()
    {
        $actual = $this->_subject->has('NotExists');
        $this->assertFalse($actual);
    }

    /**
     * Tests that remove() implements fluent interface and can be called on non-
     * existing values.
     */
    public function testRemove()
    {
        $actual = $this->_subject->remove('anyKey');
        $this->assertSame($this->_subject, $actual);
    }

    /**
     * Tests that purge() implements fluent interface
     */
    public function testPurge()
    {
        $actual = $this->_subject->purge();
        $this->assertSame($this->_subject, $actual);
    }
}
