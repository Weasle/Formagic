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
 * Tests Formagic filter public interface
 *
 * @package     Formagic\Tests
 * @author      Florian Sonnenburg
 **/
class Test_Formagic_Filter_Nl2br extends PHPUnit_Framework_TestCase
{
    /**
     * Filter instance
     * @var Formagic_Filter_Nl2br
     */
    private $filter;

    /**
     * HTML string regexp definition
     * @var array
     */
    private $match = '~line 1<br />~';

    /**
     * Test case setup.
     */
    public function setUp()
    {
        $this->filter = new Formagic_Filter_Nl2br();
    }

    /**
     * Tests if the filter instance implements correct interface
     */
    public function testInterface()
    {
        $this->assertInstanceOf('Formagic_Filter_Interface', $this->filter);
    }

    /**
     * Tests DOS newline style input
     */
    public function testFilterOnDos()
    {
        $value = "<div>line 1\n\rline 2</div>";

        $filtered = $this->filter->filter($value);
        $this->assertRegExp($this->match, $filtered);
    }

    /**
     * Tests UNIX newline style input
     */
    public function testFilterOnUnix()
    {
        $value = "<div>line 1\nline 2</div>";

        $filtered = $this->filter->filter($value);
        $this->assertRegExp($this->match, $filtered);
    }

    /**
     * Tests MAC newline style input
     */
    public function testFilterOnMac()
    {
        $value = "<div>line 1\rline 2</div>";

        $filtered = $this->filter->filter($value);
        $this->assertRegExp($this->match, $filtered);
    }
}