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
 * Load input item
 */
require_once realpath(dirname(__FILE__) . '/../../Formagic/Item/XsrfProtection.php');

/**
 * Tests Formagic upload items's public interface
 *
 * @category    Formagic
 * @package     Tests
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2011
 * @version     $Id: XsrfProtectionTest.php 173 2012-05-16 13:19:22Z meweasle $
 **/
class Formagic_Item_XsrfProtection_Test extends PHPUnit_Framework_TestCase
{
    /**
     * Session mock
     * @var Formagic_Session_Interface
     */
    private $_sessionMock;

    /**
     * Initialize test
     */
    public function setUp()
    {
        $this->_sessionMock = $this
            ->getMockBuilder('Formagic_Session_Interface')
            ->getMock();
    }

    /**
     * Test setting value
     */
    public function testSetGetSession()
    {
        $input = new Formagic_Item_XsrfProtection('name');
        $setResult = $input->setSession($this->_sessionMock);
        $this->assertSame($input, $setResult);

        $actual = $input->getSession();
        $this->assertSame($this->_sessionMock, $actual);
    }

    /**
     * Test setting session by constructor parameter
     */
    public function testSetSessionOnConstructor()
    {
        $input = new Formagic_Item_XsrfProtection(
            'name',
            array('session' => $this->_sessionMock)
        );

        $actual = $input->getSession();
        $this->assertSame($this->_sessionMock, $actual);
    }

    /**
     * Tests that getSession() always returns session object
     */
    public function testGetNewSession()
    {
        $_SESSION = array();
        $input = new Formagic_Item_XsrfProtection('name');
        $actual = $input->getSession();
        $this->assertInstanceOf('Formagic_Session_Interface', $actual);
    }

    /**
     * Tests that SessionValue Rule is built on validation
     */
    public function testValidate()
    {
        $input = new Formagic_Item_XsrfProtection(
            'name',
            array('session' => $this->_sessionMock)
        );
        $input->validate();
        $actual = $input->hasRule('SessionValue');
        $this->assertTrue($actual);
    }

    /**
     * Tests that getHtml() generates a new token and writes it to session and
     * input value
     */
    public function testGetHtml()
    {
        $itemName = 'name';
        $this->_sessionMock
            ->expects($this->once())
            ->method('set')
            ->with($itemName);
        $input = new Formagic_Item_XsrfProtection(
            $itemName,
            array('session' => $this->_sessionMock)
        );

        $this->assertEmpty($input->getValue());
        $input->getHtml();
        $this->assertInternalType('string', $input->getValue());
        $this->assertNotEmpty($input->getValue());
    }
}
