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
class Test_Formagic_Filter_Date extends PHPUnit_Framework_TestCase
{
    /**
     * Filter object
     * @var Formagic_Filter_Date
     */
    private $_filter;

    /**
     * Setup test case
     */
    public function setUp()
    {
        $this->_filter = new Formagic_Filter_Date();
    }

    /**
     * Check if filter instance implements correct interface
     */
    public function testInterface()
    {
        $this->assertInstanceOf('Formagic_Filter_Interface', $this->_filter);
    }
    
    /**
     * Test output for unix timestamp as value
     */
    public function testFilterUnixTimestamp()
    {
        $value = time();
        $expected = strftime('%x %X', $value);

        $actual = $this->_filter->filter($value);
        $this->assertEquals($expected, $actual);
    }

    /**
     * Test output for empty date
     */
    public function testFilterEmptyDate()
    {
        $value = "0000-00-00";

        $filtered = $this->_filter->filter($value);
        $this->assertEquals($filtered, 'n/a');
    }

    /**
     * Test output for empty date and time
     */
    public function testFilterEmptyDateTime()
    {
        $value = "0000-00-00 00:00:00";

        $filtered = $this->_filter->filter($value);
        $this->assertEquals($filtered, 'n/a');
    }

    /**
     * Test output for numeric zero date
     */
    public function testFilterNoDate()
    {
        $value = "0";

        $filtered = $this->_filter->filter($value);
        $this->assertEquals($filtered, '');
    }

    /**
     * Test output for correct date
     */
    public function testFilterDate()
    {
        $value = "2010-01-01 12:00:00";

        $filtered = $this->_filter->filter($value);
        $this->assertNotEquals($filtered, $value);
    }

    /**
     * Test date only output
     */
    public function testFilterOnlyDate()
    {
        $unixTimestamp = 1299900000;
        $value = strftime('%Y-%m-%d', $unixTimestamp);
        $expected = strftime('%x', $unixTimestamp);
        $actual = $this->_filter->filter($value);

        $this->assertEquals($actual, $expected);
    }
}
