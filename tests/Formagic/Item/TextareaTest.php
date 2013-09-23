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
 * Tests Formagic submit items's public interface
 *
 * @category    Formagic
 * @package     Tests
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2011
 * @version     $Id: TextareaTest.php 173 2012-05-16 13:19:22Z meweasle $
 **/
class Formagic_Item_Textarea_Test extends PHPUnit_Framework_TestCase
{
    /**
     * Test textarea HTML.
     * Test that default rows count is 7 and cols count is 50
     */
    public function testGetHtml()
    {
        $myName = 'test';

        // set source by option
        $input = new Formagic_Item_Textarea($myName);
        $html = $input->getHtml();
        $matcher = array(
            'tag' => 'textarea',
            'attributes' => array(
                'name'  => $myName,
                'id'    => $myName,
                'rows'  => 7,
                'cols'  => 50,
            )
        );
        $this->assertTag($matcher, $html);
    }

    /**
     * Test textarea HTML with content
     */
    public function testGetHtmlWithValue()
    {
        $myName = 'test';
        $myValue = 'value';

        // set source by option
        $input = new Formagic_Item_Textarea($myName);
        $input->setValue($myValue);
        $html = $input->getHtml();
        $matcher = array(
            'tag' => 'textarea',
            'content' => $myValue,
            'attributes' => array(
                'name'  => $myName,
                'id'    => $myName,
            )
        );
        $this->assertTag($matcher, $html);
    }

    /**
     * Test textarea HTML with content
     */
    public function testGetReadonlyHtml()
    {
        $myName = 'test';
        $myValue = 'value';

        // set source by option
        $input = new Formagic_Item_Textarea($myName);
        $input->setValue($myValue);
        $input->setReadonly(true);

        $html = '<span>' . $input->getHtml() . '</span>';
        $matcher = array(
            'tag' => 'span',
            'content' => $myValue,
            'child' => array(
                'tag' => 'input',
                'attributes' => array(
                    'type'  => 'hidden',
                    'name'  => $myName,
                    'id'    => $myName,
                    'value' => $myValue,
                )
            )
        );
        $this->assertTag($matcher, $html);
    }

    /**
     * Test XSS protection
     */
    public function testXssProtection()
    {
        $xss = "<script type=\"text/javascript\">alert('xss')</script>";
        $input = new Formagic_Item_Textarea('test', array(
            'value' => $xss,
        ));
        $actual  = $input->getHtml();
        $matcher = array(
            'descendant' => array(
                'tag' => 'script',
                'attributes' => array(
                    'type' => 'text/javascript'
                )
            )
        );
        $this->assertNotTag($matcher, $actual);
    }
}
