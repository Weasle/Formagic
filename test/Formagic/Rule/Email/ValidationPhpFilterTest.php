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
 * Tests Formagic email validation strategy implementation PhpFilter
 *
 * @category    Formagic
 * @package     Tests
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2011 Florian Sonnenburg
 **/
class Formagic_Rule_Email_ValidationPhpFilter_Test extends PHPUnit_Framework_TestCase
{
    /**
     * Test subject
     * @var Formagic_Rule_EmailValidation_PhpFilter
     */
    protected $_phpFilter;
    
    /**
     * Test case setup
     */
    public function setUp()
    {
        $this->_phpFilter = new Formagic_Rule_EmailValidation_PhpFilter();
    }
    
    /**
     * Tests that strategy returns bool true
     */
    public function testValidationTrue()
    {
        $result = $this->_phpFilter->isValidEmailAddress('example@example.com');
        $this->assertTrue($result);
    }
    
    /**
     * Tests that strategy returns bool false
     */
    public function testValidationFalse()
    {
        $result = $this->_phpFilter->isValidEmailAddress('exmple.com');
        $this->assertFalse($result);    
    }
}
