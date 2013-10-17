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
 * Tests Formagic translation feature
 *
 * @runTestsInSeparateProcesses
 * @category    Formagic
 * @package     Tests
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2010
 **/
class Formagic_Translator_Test extends PHPUnit_Framework_TestCase
{
    /**
     * Formagic instance
     * @var Formagic
     */
    private $_formagic;

    /**
     * Setup test case
     */
    public function setUp()
    {
        $this->_formagic = new Formagic();
    }

    public function testSetTranslator()
    {
        $this->assertAttributeEmpty('_translator', 'Formagic');
        $this->_formagic->setTranslator();
        $this->assertClassHasStaticAttribute('_translator', 'Formagic');
        $actual = $this->readAttribute('Formagic', '_translator');
        $this->assertInstanceOf('Formagic_Translator', $actual);
    }
    
    /**
     * Test that a translator object can be set by option
     */
    public function testSetTranslatorByOption()
    {
        $expectedValue = 'expectedValue';
        $translator = $this->getMock('Formagic_Translator');
        $translator
            ->expects($this->once())
            ->method('_')
            ->will($this->returnValue($expectedValue));
        $formagic = new Formagic(array('translator' => $translator));
        $actualTranslator = $formagic->getTranslator();
        $this->assertInstanceOf('Formagic_Translator', $actualTranslator);
        
        $actualValue = $actualTranslator->_('value');
        $this->assertEquals($expectedValue, $actualValue);
    }

    public function testSetTranslatorStatic()
    {
        $this->assertAttributeEmpty('_translator', 'Formagic');
        Formagic::setTranslator();
        $this->classHasStaticAttribute('_translator');
        $actual = $this->readAttribute('Formagic', '_translator');
        $this->assertInstanceOf('Formagic_Translator', $actual);
    }

    /**
     * Test basic translator accessor
     */
    public function testGetTranslator()
    {
        $translator = $this->_formagic->getTranslator();
        $this->assertInstanceOf('Formagic_Translator', $translator);
    }

    /**
     * Test setting a subclass of Formagic_Translator as translator instance
     */
    public function testSubclassed()
    {
        $translatorDefinition = new Formagic_Translator_Mock_MockupSubclass();
        $expected = $translatorDefinition->_('test');

        $this->_formagic->setTranslator($translatorDefinition);
        $translator = $this->_formagic->getTranslator();
        $this->assertInstanceOf('Formagic_Translator_Mock_MockupSubclass', $translator);

        $actual = $translator->_('test');
        $this->assertEquals($expected, $actual);
    }

    /**
     * Test setting a non-Formagic translator instance
     */
    public function testObjectCallback()
    {
        $translatorDefinition = new Formagic_Translator_Mock_MockupTranslator();
        $expected = $translatorDefinition->_('test');

        $this->_formagic->setTranslator($translatorDefinition);
        $translator = $this->_formagic->getTranslator();
        $this->assertInstanceOf('Formagic_Translator', $translator);

        $actual = $translator->_('test');
        $this->assertEquals($expected, $actual);
    }

    /**
     * Test setting a callback array instead of a translator instance
     */
    public function testArrayCallback()
    {
        $translatorDefinition = new Formagic_Translator_Mock_MockupTranslator();

        // test with default translation method ('foo::_()')
        $this->_formagic->setTranslator(array($translatorDefinition));
        $translator = $this->_formagic->getTranslator();
        $this->assertInstanceOf('Formagic_Translator', $translator);

        $expected = $translatorDefinition->_('test');
        $actual = $translator->_('test');
        $this->assertEquals($expected, $actual);

        // test with explicitly given translation method ('foo::myTranslate()')
        $this->_formagic->setTranslator(array($translatorDefinition, 'myTranslate'));
        $translator = $this->_formagic->getTranslator();
        $this->assertInstanceOf('Formagic_Translator', $translator);

        $expected = $translatorDefinition->myTranslate('test2');
        $actual = $translator->_('test2');
        $this->assertEquals($expected, $actual);
    }
}