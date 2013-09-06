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
 * Load HTML renderer class
 */
require_once realpath(dirname(__FILE__) . '/../../Formagic/Renderer/Html.php');

/**
 * Tests Formagic renderer
 *
 * @category    Formagic
 * @package     Tests
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2007-2011 Florian Sonnenburg
 * @version     $Id: HtmlTest.php 173 2012-05-16 13:19:22Z meweasle $
 **/
class Formagic_HtmlTest extends PHPUnit_Framework_TestCase
{
    /**
     *
     * @var Formagic_Renderer_Html 
     */
    private $_renderer;
    
    public function setUp()
    {
        $this->_renderer = new Formagic_Renderer_Html();
    }
    
    public function testGetTranslator()
    {
        $mockTranslator = $this->getMock('Formagic_Translator');
        $renderer = new Formagic_Renderer_Html($mockTranslator);
        $this->assertSame($mockTranslator, $renderer->getTranslator());
    }
    
    /**
     * @runInSeparateProcess
     */
    public function testGetDefaultTranslator()
    {
        $mockTranslator = $this->getMock('Formagic_Translator');
        $defaultTranslator = Formagic::setTranslator($mockTranslator);
        $renderer = new Formagic_Renderer_Html();
        $this->assertSame($mockTranslator, $renderer->getTranslator());
    }
    
    public function testSetErrorClass()
    {
        $actual = $this->_renderer->setErrorClass('css');
        $this->assertSame($this->_renderer, $actual);
    }
    
    /**
     * Integration test
     */
    public function testErrorneousForm()
    {
        $errorMessage = 'testmessage';
        $errorClass = 'errorclass';
        $mockRule = $this->getMock(
            'Formagic_Rule_Abstract',
            array('getMessage', 'validate')
        );
        $mockRule->expects($this->once())
                ->method('getMessage')
                ->will($this->returnValue($errorMessage));
        $item = $this->getMock(
            'Formagic_Item_Abstract',
            array('getViolatedRules'),
            array('testItem')
        );
        $item->expects($this->once())
                ->method('getViolatedRules')
                ->will($this->returnValue(array($mockRule)));
        
        $form = new Formagic();
        $form->addItem($item);
        $this->_renderer->setErrorClass($errorClass);
        
        $actual = $this->_renderer->render($form);
        $matcher = array(
            'tag' => 'ul',
            'attributes' => array('class' => $errorClass),
            'child' => array(
                'tag'     => 'li',
                'content' => $errorMessage
            )
        );
        $this->assertTag($matcher, $actual);
    }
    
    public function testGetSetDefaultContainerRowTemplate()
    {
        $expectedTpl = 'any';
        $actual = $this->_renderer->setContainerRowTemplate($expectedTpl);
        $this->assertSame($this->_renderer, $actual);
        
        $actualTpl = $this->_renderer->getContainerRowTemplate();
        $this->assertEquals($expectedTpl, $actualTpl);
        
        $actualTpl = $this->_renderer->getContainerRowTemplate('test');
        $this->assertEquals($expectedTpl, $actualTpl);
    }
    
    public function testGetSetSpecificContainerRowTemplateByString()
    {
        $expectedTpl = 'any';
        $specificItemName = 'itemName';
        $actual = $this->_renderer->setContainerRowTemplate($expectedTpl, $specificItemName);
        $this->assertSame($this->_renderer, $actual);
        
        $actualTpl = $this->_renderer->getContainerRowTemplate($specificItemName);
        $this->assertEquals($expectedTpl, $actualTpl);
        
        $actualTpl = $this->_renderer->getContainerRowTemplate('test');
        $this->assertNotEquals($expectedTpl, $actualTpl);
        
        $actualTpl = $this->_renderer->getContainerRowTemplate();
        $this->assertNotEquals($expectedTpl, $actualTpl);
    }

    public function testGetSetTemplates()
    {
        $templates = array(
            'ContainerRowTemplate',
            'ContainerWrapperTemplate',
            'ItemRowTemplate',
            'ItemLabelTemplate',
            'ItemErrorTemplate',
            'ItemErrorWrapperTemplate',
            'MandatoryMarkerTemplate',
            'MandatoryMarkerTemplate'
        );
        foreach($templates as $template) {
            $this->_testTemplate($template);
        }
    }
    
    public function testGetSetFormWrapperTemplate()
    {
        $expectedTpl = 'any';
        $actual = $this->_renderer->setFormWrapperTemplate($expectedTpl);
        $this->assertSame($this->_renderer, $actual);
        
        $actualTpl = $this->_renderer->getFormWrapperTemplate();
        $this->assertEquals($expectedTpl, $actualTpl);
    }
    
    public function testFormName()
    {
        //set by option
        $name = 'testname2';
        $formagic = new Formagic(array(
            'name' => $name, 
            'renderer' => $this->_renderer,
            'attributes' => array('name' => $name)
        ));
        
        $html = $formagic->render();
        $matcher = array(
            'tag'        => 'form',
            'attributes' => array('name' => $name, 'id' => $name)
        );
        $this->assertTag($matcher, $html);
    }
    
    public function testDisabledItem()
    {
        $formagic = new Formagic(array(
            'renderer' => $this->_renderer
        ));
        $formagic->addItem('input', 'test', array('disable' => true));
        
        $actual = $formagic->render();
        $matcher = array(
            'tag'        => 'input',
            'attributes' => array('type' => 'text', 'id' => 'test')
        );
        $this->assertNotTag($matcher, $actual);
    }
    
    public function testSubContainer()
    {
        $formagic = new Formagic(array(
            'renderer' => $this->_renderer
        ));
        $container = new Formagic_Item_Container('sub');
        $container->addItem('input', 'test');
        $formagic->addItem($container);
        
        $actual = $formagic->render();
        $matcher = array(
            'tag'        => 'table',
            'attributes' => array('id' => 'sub'),
            'child' => array(
                'tag' => 'tr',
                'child' => array(
                    'tag' => 'td',
                    'child' => array(
                        'tag' => 'input',
                        'attributes' => array('id' => 'test', 'name' => 'test')
                    )
                )
            )
        );
        $this->assertTag($matcher, $actual);
    }
    
    public function testHiddenInput()
    {
        $formagic = new Formagic(array(
            'renderer' => $this->_renderer
        ));
        $formagic->addItem('hidden', 'test');
        
        $actual = $formagic->render();
        $matcher = array(
            'tag'        => 'form',
            'child' => array(
                'tag' => 'input',
                'attributes' => array(
                    'id' => 'test', 
                    'name' => 'test', 
                    'type' => 'hidden'
                )
            )
        );
        $this->assertTag($matcher, $actual);
    }

    /**
     * Integration test
     */
    public function testItemLabelWithoutLabel()
    {
        $item = $this->getMock(
            'Formagic_Item_Abstract',
            array('getLabel'),
            array('testItem')
        );
        $item->expects($this->once())
                ->method('getLabel')
                ->will($this->returnValue(''));
        
        $form = new Formagic();
        $form->addItem($item);
        
        $actual = $this->_renderer->render($form);
        $this->assertTrue(is_integer(strpos($actual, '<td>&nbsp;</td>')));
    }

    /**
     * Integration test
     */
    public function testItemLabelWithLabelWithError()
    {
        $errorMessage = 'testmessage';
        $label = 'label';
        $itemName = 'testItem';
        $mockRule = $this->getMock(
            'Formagic_Rule_Abstract',
            array('getMessage', 'validate')
        );
        $mockRule->expects($this->once())
                ->method('getMessage')
                ->will($this->returnValue($errorMessage));
        $item = $this->getMock(
            'Formagic_Item_Abstract',
            array('getViolatedRules', 'getLabel'),
            array($itemName)
        );
        $item->expects($this->once())
                ->method('getViolatedRules')
                ->will($this->returnValue(array($mockRule)));
        $item->expects($this->once())
                ->method('getLabel')
                ->will($this->returnValue($label));
        
        $form = new Formagic();
        $form->addItem($item);
        
        $actual = $this->_renderer->render($form);
        $matcher = array(
            'tag' => 'label',
            'content' => $label,
            'attributes' => array(
                'for' => $itemName, 
                'class' => 'formagicError', 
            )
        );
        $this->assertTag($matcher, $actual);
    }
    
    /**
     * Integration test
     */
    public function testMandatoryMarker()
    {
        $label = 'label';
        $itemName = 'testItem';
        $item = $this->getMock(
            'Formagic_Item_Abstract',
            array('hasRule', 'getLabel'),
            array($itemName)
        );
        $item->expects($this->once())
                ->method('hasRule')
                ->with('mandatory')
                ->will($this->returnValue(true));
        $item->expects($this->once())
                ->method('getLabel')
                ->will($this->returnValue($label));
        
        $form = new Formagic();
        $form->addItem($item);
        
        $actual = $this->_renderer->render($form);
        $matcher = array(
            'tag' => 'label',
            'content' => $label,
            'attributes' => array(
                'for' => $itemName, 
            ),
            'child' => array(
                'tag' => 'span',
                'content' => '*',
                'attributes' => array(
                    'class' => 'mandatory'
                ),
            )
        );
        $this->assertTag($matcher, $actual);
    }

    private function _testTemplate($functionName)
    {
        $renderer = new Formagic_Renderer_Html();
        
        $setMethod = 'set' . $functionName;
        $getMethod = 'get' . $functionName;
        
        $expectedTpl = 'any';
        $specificItem = $this->getMock(
            'Formagic_Item_Abstract', 
            array('getName'), 
            array('myItem')
        );
        $specificItem->expects($this->any())
                ->method('getName')
                ->will($this->returnValue('myItem'));
        
        $actual = $renderer->$setMethod($expectedTpl, $specificItem);
        $this->assertSame($renderer, $actual);
        
        $actualTpl = $renderer->$getMethod($specificItem);
        $this->assertEquals($expectedTpl, $actualTpl);
        
        $actualTplOther = $renderer->$getMethod('test');
        $this->assertNotEquals($expectedTpl, $actualTplOther);
        
        $actualTplDefault = $renderer->$getMethod();
        $this->assertNotEquals($expectedTpl, $actualTplDefault);
        
        $this->assertEquals($actualTplOther, $actualTplDefault);
    }
    
    public function testSetGetDefaultContainerLabelTemplate()
    {
        $expected = 'testContainerLabelTemplate';
        $renderer = new Formagic_Renderer_Html();
        $renderer->setContainerLabelTemplate($expected);
        $actual = $renderer->getContainerLabelTemplate();
        $this->assertEquals($expected, $actual);
    }
    
    public function testSetGetSpecificContainerLabelTemplate()
    {
        $expected = 'testContainerLabelTemplate';
        $renderer = new Formagic_Renderer_Html();
        $renderer->setContainerLabelTemplate($expected, 'test');
        $actual = $renderer->getContainerLabelTemplate('test');
        $this->assertEquals($expected, $actual);
    }
}
