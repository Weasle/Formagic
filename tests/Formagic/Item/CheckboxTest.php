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
 * @author      Florian Sonnenburg
 * @copyright   2007-2014 Florian Sonnenburg
 * @license     http://www.formagic-php.net/license-agreement/   New BSD License
 */

/**
 * Tests Formagic checkbox input items's public interface
 *
 * @package     Formagic\Tests
 * @author      Florian Sonnenburg
 **/
class Formagic_Item_Checkbox_Test extends PHPUnit_Framework_TestCase
{
    /**
     * Tests that checkbox HTML contains correct type and content
     */
    public function testGetHtml()
    {
        $myName = 'test';
        $input = new Formagic_Item_Checkbox($myName, array(
        ));
        $html = $input->getHtml();

        $matcher = array(
            'tag' => 'input',
            'attributes' => array(
                'type' => 'checkbox',
                'name' => $myName,
                'id' => $myName
            )
        );
        $this->assertTag($matcher, $html);
    }

    /**
     * Tests that given value is transported correctly
     */
    public function testGetHtmlWithValue()
    {
        // test with simple value
        $myName = 'test';
        $input = new Formagic_Item_Checkbox($myName, array(
            'value' => 1
        ));
        $html = $input->getHtml();

        $matcher = array(
            'tag' => 'input',
            'attributes' => array(
                'type'      => 'checkbox',
                'name'      => $myName,
                'id'        => $myName,
                'checked'   => 'checked',
                'value'     => '1'
            )
        );
        $this->assertTag($matcher, $html);

        $matcher = array(
            'tag' => 'input',
            'attributes' => array(
                'type'      => 'hidden',
                'name'      => $myName,
                'value'     => '0'
            )
        );
        $this->assertTag($matcher, $html);
    }

    /**
     * Tests read only output
     */
    public function testReadonly()
    {
        $myName = 'test';
        $input = new Formagic_Item_Checkbox($myName, array(
            'readonly' => true,
        ));

        $html = '<div>' . $input->getHtml() . '</div>';
        $matcher = array(
            'tag'     => 'div',
            'content' => '[_]',
            'child'   => array(
                'tag'   => 'input',
                'attributes' => array(
                    'type' => 'hidden',
                    'name' => $myName,
                    'id' => $myName,
                    'value' => '0'
                )
            )
        );
        $this->assertTag($matcher, $html);
    }

    /**
     * Tests read only output with given value
     */
    public function testReadonlyWithValue()
    {
        $myName = 'test';
        $value = 'myValue';
        $input = new Formagic_Item_Checkbox($myName, array(
            'readonly' => true,
            'value' => '1'
        ));

        $html = '<div>' . $input->getHtml() . '</div>';
        $matcher = array(
            'tag'     => 'div',
            'content' => '[X]',
            'child'   => array(
                'tag'   => 'input',
                'attributes' => array(
                    'type' => 'hidden',
                    'name' => $myName,
                    'id' => $myName,
                    'value' => '1'
                )
            )
        );
        $this->assertTag($matcher, $html);
    }
}
