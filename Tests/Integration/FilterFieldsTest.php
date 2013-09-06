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
 * @copyright   Copyright (c) 2007-2012 Florian Sonnenburg
 * @license     http://www.formagic-php.net/license-agreement/   New BSD License
 */

/**
 * Tests item behavior
 *
 * @category    Formagic
 * @package     Tests
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2012
 * @version     $Id: $
 **/
class Formagic_Integration_FilterFieldsTest extends PHPUnit_Framework_TestCase
{
    /**
     *
     * @var Formagic
     */
    private $_formagic;
    
    /**
     * Init
     */
    public function setUp()
    {
        $this->_formagic = new Formagic(
            array(
                'filters' => array('trim'),
                'trackSubmission' => false
            )
        );
    }
    
    /**
     * Tests that filtering values of hidden fields works as expected
     */
    public function testFilterHiddenField()
    {
        $initial = '  test  ';
        $this->_formagic->addItem('hidden', 'test', array(
            'value' => $initial
        ));
        $result = $this->_formagic->render();
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Tests that filtering integer values works as expected
     */
    public function testFilterInteger()
    {
        $initial = 1;
        $this->_formagic->addItem('text', 'test', array(
            'value' => $initial
        ));
        $result = $this->_formagic->render();
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Tests that chained filters work as intended
     */
    public function testFilterFloat()
    {
        $expected = '1.1';
        $_POST = array('float' => '1,1');
        $this->_formagic->loadClass('Formagic_Filter_Replace');
        $this->_formagic->loadClass('Formagic_Rule_Mandatory');
        $this->_formagic->loadClass('Formagic_Rule_Numeric');
        
        $replaceFilter = new Formagic_Filter_Replace(array(
            ',' => '.'
        ));
        $this->_formagic->addItem('input', 'float', array(
            'filters' => array($replaceFilter),
        ));
        
        $result = $this->_formagic->validate();
        $values = $this->_formagic->getValues();
        $this->assertEquals($expected, $values['float']);
    }
}
