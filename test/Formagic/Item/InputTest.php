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
 * Tests Formagic text input items's public interface
 *
 * @category    Formagic
 * @package     Tests
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2011
 **/
class Formagic_Item_Input_Test extends PHPUnit_Framework_TestCase
{
    public function testGetHtmlDefault()
    {
        // test without value
        $input = new Formagic_Item_Input('test');
        $html = $input->getHtml();

        $matcher = array(
            'tag'        => 'input',
            'attributes' => array(
                'name' => 'test',
                'id' => 'test',
                'type' => 'text',
                'value' => ''
            )
        );
        $this->assertTag($matcher, $html);

        // test with simple value
        $value = 'myValue';
        $input->setValue($value);
        $html = $input->getHtml();

        $matcher = array(
            'tag'        => 'input',
            'attributes' => array(
                'value' => $value
            )
        );
        $this->assertTag($matcher, $html);

        // test with complex value
        $value = '<script type="text/javascript">alert(\'xss\')</script>';
        $actualValue = '&lt;script type=&quot;text/javascript&quot;&gt;'
            . 'alert(\'xss\')&lt;/script&gt;';
        $input->setValue($value);
        $html = $input->getHtml();
        $this->assertTrue(strpos($html, $actualValue) !== false);
    }

    public function testGetHtmlReadonly()
    {
        $value = 'myValue';
        $input = new Formagic_Item_Input('test', array(
            'readonly' => true,
            'value'    => $value
        ));
        $html = '<div>' . $input->getHtml() . '</div>';

        $matcher = array(
            'tag'        => 'div',
            'content' => $value,
            'child' => array(
            'tag'   => 'input',
                'id'      => 'test',
                'attributes' => array(
                    'name' => 'test',
                    'id' => 'test',
                    'type' => 'hidden',
                    'value' => $value
                )
            )
        );
        $this->assertTag($matcher, $html);
    }
}
