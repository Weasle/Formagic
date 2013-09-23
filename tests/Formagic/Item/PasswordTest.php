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
 * Tests Formagic password input items's public interface
 *
 * @category    Formagic
 * @package     Tests
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2011
 * @version     $Id: PasswordTest.php 173 2012-05-16 13:19:22Z meweasle $
 **/
class Formagic_Item_Password_Test extends PHPUnit_Framework_TestCase
{
    /**
     * Test HTML output of password field
     */
    public function testGetHtml()
    {
        $myName  = 'test';
        $myValue = 'testPassword';
        $input = new Formagic_Item_Password($myName);
        $input->addAttribute('value', $myValue);
        $html = $input->getHtml();

        $matcher = array(
            'tag' => 'input',
            'attributes' => array(
                'type' => 'password',
                'name' => $myName,
                'id' => $myName,
            )
        );
        $this->assertTag($matcher, $html);

        // test that password value is not displayed
        $this->assertEquals(0, preg_match('/' . $myValue . '/', $html));
    }

    /**
     * Test that password field never shows it's value
     */
    public function testNeverShowValue()
    {
        $myName = 'test';
        $input = new Formagic_Item_Password($myName, array('value' => 'myValue'));
        $html = $input->getHtml();

        $this->assertTrue(strpos($html, 'myValue') === false);
    }

    /**
     * Test readonly output of password field
     */
    public function testGetReadonlyOutput()
    {
        $myName = 'test';
        $myValue = 'm';
        $input = new Formagic_Item_Password($myName, array(
            'readonly' => true,
            'value' => 'myTest'));
        $html = $input->getHtml();

        $matcher = array(
            'tag' => 'span',
            'content' => '*',
            'attributes' => array(
                'id' => $myName,
            )
        );
        $this->assertTag($matcher, $html);
    }
}
