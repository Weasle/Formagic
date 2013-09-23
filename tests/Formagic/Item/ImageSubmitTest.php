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
 * Tests Formagic image submit input items's public interface
 *
 * @category    Formagic
 * @package     Tests
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2011
 * @version     $Id: ImageSubmitTest.php 173 2012-05-16 13:19:22Z meweasle $
 **/
class Formagic_Item_ImageSubmit_Test extends PHPUnit_Framework_TestCase
{
    /**
     * Test public interface
     */
    public function testSetSource()
    {
        $input = new Formagic_Item_ImageSubmit('test');
        $res = $input->setSource('test.gif');
        $this->assertSame($input, $res);
    }

    /**
     * Test image submit HTML
     */
    public function testGetHtml()
    {
        // set source by option
        $myName = 'test';
        $source = 'test.gif';
        $input = new Formagic_Item_ImageSubmit($myName, array(
            'source' => $source
        ));
        $html = $input->getHtml();
        $matcher = array(
            'tag' => 'input',
            'attributes' => array(
                'src'  => $source,
                'type' => 'image',
                'name' => $myName,
                'id'   => $myName
            )
        );
        $this->assertTag($matcher, $html);

        // set source by method
        $input = new Formagic_Item_ImageSubmit($myName);
        $input->setSource($source);
        
        $html = $input->getHtml();
        $matcher = array(
            'tag' => 'input',
            'attributes' => array(
                'src'  => $source,
                'type' => 'image',
                'name' => $myName,
                'id'   => $myName
            )
        );
        $this->assertTag($matcher, $html);
    }

    /**
     * @expectedException Formagic_Exception
     */
    public function testNoSourceException()
    {
        // set source by option
        $myName = 'test';
        $input = new Formagic_Item_ImageSubmit($myName);
        $input->getHtml();
    }
    
    /**
     * Test submit triggered
     */
    public function testIsTriggered()
    {
        $myName = 'test';
        $input = new Formagic_Item_ImageSubmit($myName, array(
            'value'  => 'test'
        ));
        $isTriggered = $input->isTriggered();
        $this->assertTrue($isTriggered);

        $input = new Formagic_Item_ImageSubmit($myName);
        $isTriggered = $input->isTriggered();
        $this->assertFalse($isTriggered);
    }

    /**
     * Test setting correct click coordinates
     */
    public function testSetClickCoordinates()
    {
        $coordinates = array('x' => 1, 'y' => 1);
        $input = new Formagic_Item_ImageSubmit('test');
        $actual = $input->setClickCoordinates($coordinates);
        $this->assertSame($input, $actual);
    }

    /**
     * Click coordinates have to be array. Test if an error is thrown if it
     * is not.
     *
     * @expectedException PHPUnit_Framework_Error
     */
    public function testSetWrongClickCoordinates()
    {
        $coordinates = 'x1y1';
        $input = new Formagic_Item_ImageSubmit('test');
        $input->setClickCoordinates($coordinates);
    }

    /**
     * Test getting click coordinates
     */
    public function testGetClickCoordinates()
    {
        $expected = array('x' => 1, 'y' => 1);
        $input = new Formagic_Item_ImageSubmit('test');
        $input->setClickCoordinates($expected);
        $actual = $input->getClickCoordinates();
        $this->assertEquals($expected, $actual);
    }
}
