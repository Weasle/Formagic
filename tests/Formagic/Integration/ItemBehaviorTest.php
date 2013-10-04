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
 * Tests item behavior
 *
 * @category    Formagic
 * @package     Tests
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2010
 **/
class Formagic_Integration_ItemBehaviorTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test item creation by Formagic item factory
     */
    public function testFixedItem()
    {
        $expected = 'initialValue';
        $_POST = array('test' => 'submittedValue');
        $item = Formagic::createItem('text', 'test', array(
            'value' => $expected,
            'fixed' => true,
        ));
        $form = new Formagic(array(
            'method' => 'post',
            'trackSubmission' => false
        ));
        $form->addItem($item);
        $form->validate();
        
        $actual = $item->getValue();
        $this->assertEquals($expected, $actual);
    }

}
