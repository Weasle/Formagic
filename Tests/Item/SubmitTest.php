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
require_once realpath(dirname(__FILE__) . '/../../Formagic/Item/Submit.php');

/**
 * Tests Formagic submit items's public interface
 *
 * @category    Formagic
 * @package     Tests
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2011
 * @version     $Id: SubmitTest.php 173 2012-05-16 13:19:22Z meweasle $
 **/
class Formagic_Item_Submit_Test extends PHPUnit_Framework_TestCase
{
    /**
     * Test getLabel()
     */
    public function testGetLabel()
    {
        $input = new Formagic_Item_Submit('test');
        $actual = $input->getLabel();
        $this->assertEquals('', $actual);
    }

    /**
     * Test image submit HTML
     */
    public function testGetHtml()
    {
        $myName = 'test';
        $label  = 'testLabel';

        // set source by option
        $input = new Formagic_Item_Submit($myName, array('label' => $label));
        $html = $input->getHtml();
        $matcher = array(
            'tag' => 'input',
            'attributes' => array(
                'type'  => 'submit',
                'name'  => $myName,
                'id'    => $myName,
                'value' => $label
            )
        );
        $this->assertTag($matcher, $html);
    }

    /**
     * Test triggered status
     */
    public function testIsTriggered()
    {
        $value = 'testValue';
        $input = new Formagic_Item_Submit('test', array('label' => 'myLabel'));
        $actual = $input->isTriggered();
        $this->assertFalse($actual);

        $input->setValue($value);
        $actual = $input->isTriggered();
        $this->assertTrue($actual);
    }
}
