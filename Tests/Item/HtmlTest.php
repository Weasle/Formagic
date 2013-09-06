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
require_once realpath(dirname(__FILE__) . '/../../Formagic/Item/Html.php');

/**
 * Tests Formagic HTML input items's public interface
 *
 * @category    Formagic
 * @package     Tests
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2011
 * @version     $Id: HtmlTest.php 173 2012-05-16 13:19:22Z meweasle $
 **/
class Formagic_Item_Html_Test extends PHPUnit_Framework_TestCase
{
    /**
     * Test HTML item output
     */
    public function testGetHtml()
    {
        $myName  = 'test';
        $myValue = 'testValue<i>italic</i>';
        $input = new Formagic_Item_Html($myName, array('value' => $myValue));
        $html = $input->getHtml();

        $matcher = array(
            'tag' => 'div',
            'content' => 'testValue',
            'attributes' => array(
                'id' => $myName,
            ),
            'child' => array(
                'tag' => 'i',
                'content' => 'italic',
            )
        );
        $this->assertTag($matcher, $html);
    }

    /**
     * Test HTML output in "raw" mode
     */
    public function testGetHtmlRaw()
    {
        $myName  = 'test';
        $myValue = 'testValue';
        $input = new Formagic_Item_Html($myName, array(
            'value' => $myValue,
            'raw' => true
        ));
        $html = $input->getHtml();
        $this->assertEquals($myValue, $html);
    }
}
