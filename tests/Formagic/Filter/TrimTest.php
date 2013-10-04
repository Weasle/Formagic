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
 * Tests Formagic filter public interface
 *
 * @category    Formagic
 * @package     Tests
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2010
 **/
class Test_Formagic_Filter_Trim extends PHPUnit_Framework_TestCase
{
    /**
     * Tests if the filter instance implements correct interface
     */
    public function testInterface()
    {
        $filter = new Formagic_Filter_Replace(array());
        $this->assertInstanceOf('Formagic_Filter_Interface', $filter);
    }

    /**
     * Tests filter output
     */
    public function testFilter()
    {
        $value = ' test ';

        $filter = new Formagic_Filter_Trim();
        $this->assertInstanceOf('Formagic_Filter_Interface', $filter);

        $filtered = $filter->filter($value);
        $this->assertEquals('test', $filtered);
    }
}