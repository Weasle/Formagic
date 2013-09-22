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
 * Tests Formagic button input items's public interface
 *
 * @category    Formagic
 * @package     Tests
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2011
 * @version     $Id: ButtonTest.php 173 2012-05-16 13:19:22Z meweasle $
 **/
class Formagic_Item_Button_Test extends PHPUnit_Framework_TestCase
{
    public function testGetHtml()
    {
        $label = 'myLabel';
        $myName = 'test';
        $input = new Formagic_Item_Button($myName, array(
            'label' => $label
        ));
        
        $html = $input->getHtml();

        $input->setReadonly(true);
        $htmlReadonly = $input->getHtml();
        $this->assertSame($html, $htmlReadonly);

        $matcher = array(
            'tag' => 'button',
            'attributes' => array(
                'type' => 'button',
                'name' => $myName,
                'id' => $myName
            )
        );
        $this->assertTag($matcher, $html);
    }

    public function testGetHtmlWithValue()
    {
        // test with simple value
        $value = 'myValue';
        $input = new Formagic_Item_Button('test', array('label' => 'myLabel'));
        $htmlWithoutValue = $input->getHtml();

        $input->setValue($value);
        $htmlWithValue = $input->getHtml();
        $this->assertSame($htmlWithValue, $htmlWithoutValue);
    }
    
    /**
     * Test that getLabel() returns empty string
     */
    public function testGetLabel()
    {
        $nonExpected = 'myLabel';
        $input = new Formagic_Item_Button('test', array(
            'label' => $nonExpected));
        $actual = $input->getLabel();
        $this->assertEquals('', $actual);
    }
}
