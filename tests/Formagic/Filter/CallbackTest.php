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
 * @copyright   Copyright (c) 2007-2010 Florian Sonnenburg
 * @license     http://www.formagic-php.net/license-agreement/   New BSD License
 */

/**
 * Tests Formagic callback filter
 *
 * @category    Formagic
 * @package     Tests
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2010
 * @version     $Id: CallbackTest.php 173 2012-05-16 13:19:22Z meweasle $
 **/
class Test_Formagic_Filter_Callback extends PHPUnit_Framework_TestCase
{
    /**
     * Test if callback filter object implements correct interface
     */
    public function testInterface()
    {
        $filter = new Formagic_Filter_Callback(null);
        $this->assertInstanceOf('Formagic_Filter_Interface', $filter);
    }

    /**
     * Test standard callback without parameters
     */
    public function testCallbackWithoutArguments()
    {
        $value = ' test ';
        $callback = 'trim';
        $filter = new Formagic_Filter_Callback($callback);
        $filteredValue = $filter->filter($value);

        $this->assertEquals($filteredValue, 'test');
    }

    /**
     * Test extended callback with parameters.
     *
     * Tests if placeholders are resolved (%VALUE% is set to filtered value).
     */
    public function testCallbackWithArguments()
    {
        $tpl = 'insert here: %s';
        $value = 'test';
        $callback = 'sprintf';
        $filter = new Formagic_Filter_Callback($callback, array($tpl, '%VALUE%'));
        $filteredValue = $filter->filter($value);
        
        $this->assertEquals($filteredValue, sprintf($tpl, $value));
    }
}