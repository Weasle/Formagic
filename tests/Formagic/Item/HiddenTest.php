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
 * Tests Formagic hidden input items's public interface
 *
 * @package     Formagic\Tests
 * @author      Florian Sonnenburg
 **/
class Formagic_Item_Hidden_Test extends PHPUnit_Framework_TestCase
{
    public function testGetHtml()
    {
        $myName  = 'test';
        $myValue = 'testValue';
        $input = new Formagic_Item_Hidden($myName, array('value' => $myValue));
        $html = $input->getHtml();

        $matcher = array(
            'tag' => 'input',
            'attributes' => array(
                'value' => $myValue,
                'type' => 'hidden',
                'name' => $myName,
                'id' => $myName,
            )
        );
        $this->assertTag($matcher, $html);
    }
}
