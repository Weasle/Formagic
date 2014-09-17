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
 * Tests Formagic submit items's public interface
 *
 * @package     Formagic\Tests
 * @author      Florian Sonnenburg
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
