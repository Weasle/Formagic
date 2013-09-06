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
 * Load filter class file
 */
require_once dirname(__FILE__) . '/../../Formagic/Filter/Nl2br.php';

/**
 * Tests Formagic filter public interface
 *
 * @category    Formagic
 * @package     Tests
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2010
 * @version     $Id: Nl2brTest.php 173 2012-05-16 13:19:22Z meweasle $
 **/
class Test_Formagic_Filter_Nl2br extends PHPUnit_Framework_TestCase
{
    /**
     * Filter instance
     * @var Formagic_Filter_Nl2br
     */
    private $_filter;

    /**
     * HTML string matcher definition
     * @var array
     */
    private $_matcher;

    /**
     * Test case setup.
     * 
     * All tests assume that one <br /> is to be inserted (actually, it does
     * not matter if the BR tag is HTML or XHTML syntax
     */
    public function setUp()
    {
        $this->_filter = new Formagic_Filter_Nl2br();
        $this->_matcher = array('tag' => 'div', 'children' => array(
            'count' => 1,
            'only' => array('tag' => 'br')
        ));
    }

    /**
     * Tests if the filter instance implements correct interface
     */
    public function testInterface()
    {
        $this->assertInstanceOf('Formagic_Filter_Interface', $this->_filter);
    }

    /**
     * Tests DOS newline style input
     */
    public function testFilterOnDos()
    {
        $value = "<div>line 1\n\rline 2</div>";

        $filtered = $this->_filter->filter($value);
        $this->assertTag($this->_matcher, $filtered);
    }

    /**
     * Tests UNIX newline style input
     */
    public function testFilterOnUnix()
    {
        $value = "<div>line 1\nline 2</div>";

        $filtered = $this->_filter->filter($value);
        $this->assertTag($this->_matcher, $filtered);
    }

    /**
     * Tests MAC newline style input
     */
    public function testFilterOnMac()
    {
        $value = "<div>line 1\rline 2</div>";

        $filtered = $this->_filter->filter($value);
        $this->assertTag($this->_matcher, $filtered);
    }
}