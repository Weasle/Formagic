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
 * Tests Formagic renderer
 *
 * @package     Formagic\Tests
 * @author      Florian Sonnenburg
 **/
class Formagic_ArrayTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test subject
     * @var Formagic_Renderer_Html 
     */
    private $_renderer;
    
    /**
     * Test initialization
     */
    public function setUp()
    {
        $this->_renderer = new Formagic_Renderer_Array();
    }
    
    /**
     * Tests that most basic formagic properties are returned by renderer
     */
    public function testSimpleRendering()
    {
        $form = new Formagic();
        $form->setMethod('post');
        $form->setFormAction('action');
        
        $actual = $this->_renderer->render($form);
        $this->assertInternalType('array', $actual);
        
        $this->assertArrayHasKey('hasErrors', $actual);
        $this->assertArrayHasKey('method', $actual);
        $this->assertArrayHasKey('action', $actual);
        $this->assertArrayHasKey('formagicFields', $actual);
        
        $this->assertFalse($actual['hasErrors']);
        $this->assertEquals('post', $actual['method']);
        $this->assertEquals('action', $actual['action']);
        $this->assertEquals('', $actual['formagicFields']);
    }
    
    /**
     * Tests that disabled items are ignored
     */
    public function testDisableItemIgnore()
    {
        $form = new Formagic();
        $actualNoItem = $this->_renderer->render($form);
        
        $item = $this->getMock(
            'Formagic_Item_Abstract',
            array('getName', 'isDisabled'),
            array('sub')
        );
        $item->expects($this->any())
                ->method('getName')
                ->will($this->returnValue('test'));
        $item->expects($this->any())
                ->method('isDisabled')
                ->will($this->returnValue(true));
        $form->addItem($item);
        
        $actualWithItem = $this->_renderer->render($form);
        $this->assertEquals($actualNoItem, $actualWithItem);
    }
    
    /**
     * Tests that sub containers do not appear in result array
     */
    public function testSubContainer()
    {
        $form = new Formagic();
        $actualNoContainer = $this->_renderer->render($form);
        
        $container = $this->getMock(
            'Formagic_Item_Container',
            array('getName'),
            array('sub')
        );
        $container->expects($this->any())
                ->method('getName')
                ->will($this->returnValue('sub'));
        $form = new Formagic();
        $form->addItem($container);
        $actualContainer = $this->_renderer->render($form);
        $this->assertEquals($actualContainer, $actualNoContainer);
    }
    
    /**
     * Tests that Formagic hidden items are stored in result array
     */
    public function testResultHasFormagicItems()
    {
        $form = new Formagic();
        $form->setRenderer('array');
        $form->setTrackSubmission(true);
        $array = $form->render();
        $this->assertArrayHasKey('formagicFields', $array);
    }
    
    /**
     * Tests that item properties are stored in result array
     */
    public function testRendererResultHasItemProperties()
    {
        $expectedErrorMessage = 'errorMessage';
        
        // mock violated rule
        $mockRule = $this->getMock(
            'Formagic_Rule_Abstract',
            array('getMessage', 'validate')
        );
        $mockRule->expects($this->once())
                ->method('getMessage')
                ->will($this->returnValue($expectedErrorMessage));
        
        $expectedName = 'mockItem';
        $expectedAttributes = array('id' => 'mockItem', 'name' => 'mockItem');
        $expectedFilteredValue = 'myFilteredValue';
        $expectedUnfilteredValue = 'myUnfilteredValue';
        $expectedHtml = 'html';
        $expectedLabel = 'testTabel';
        
        // mock item with defined properties
        $item = $this->getMock(
            'Formagic_Item_Abstract',
            array(
                'getName', 
                'getAttributes', 
                'getValue', 
                'getUnfilteredValue',
                'getViolatedRules',
                'getHtml',
                'getLabel'
            ),
            array($expectedName)
        );
        
        $item->expects($this->any())
                ->method('getName')
                ->will($this->returnValue($expectedName));
        $item->expects($this->any())
                ->method('getAttributes')
                ->will($this->returnValue($expectedAttributes));
        $item->expects($this->any())
                ->method('getValue')
                ->will($this->returnValue($expectedFilteredValue));
        $item->expects($this->any())
                ->method('getUnfilteredValue')
                ->will($this->returnValue($expectedUnfilteredValue));
        $item->expects($this->once())
                ->method('getViolatedRules')
                ->will($this->returnValue(array($mockRule)));
        $item->expects($this->once())
                ->method('getHtml')
                ->will($this->returnValue($expectedHtml));
        $item->expects($this->once())
                ->method('getLabel')
                ->will($this->returnValue($expectedLabel));
        
        $form = new Formagic();
        $form->setTrackSubmission(true);
        $form->addItem($item);
        $rendererResult = $this->_renderer->render($form);
        $this->assertArrayHasKey('mockItem', $rendererResult['items']);
        
        $itemArray = $rendererResult['items']['mockItem'];
        
        $this->assertArrayHasKey('attributes', $itemArray);
        $this->assertArrayHasKey('name', $itemArray);
        $this->assertArrayHasKey('value', $itemArray);
        $this->assertArrayHasKey('unfilteredValue', $itemArray);
        $this->assertArrayHasKey('error', $itemArray);
        $this->assertArrayHasKey('html', $itemArray);
        $this->assertArrayHasKey('label', $itemArray);
        $this->assertArrayHasKey('isMandatory', $itemArray);
        
        $this->assertEquals($itemArray['attributes'], $expectedAttributes);
        $this->assertEquals($itemArray['name'], $expectedName);
        $this->assertEquals($itemArray['value'], $expectedFilteredValue);
        $this->assertEquals($itemArray['unfilteredValue'], $expectedUnfilteredValue);
        $this->assertEquals($itemArray['error'], array($expectedErrorMessage));
        $this->assertEquals($itemArray['html'], $expectedHtml);
        $this->assertEquals($itemArray['label'], $expectedLabel);
        $this->assertEquals($itemArray['isMandatory'], false);
    }
    
    /**
     * Tests that item value sanitation works.
     */
    public function testSanitize()
    {
        $expectedKey = 'key';
        $expectedValue = '&quot;value&quot;';
        $expectedItemValue = array($expectedKey => $expectedValue);
        $actualItemValue = array('key' => '"value"');

        $item = $this->getMock(
            'Formagic_Item_Abstract',
            array('getName', 'getValue'),
            array('mockItem')
        );
        $item->expects($this->any())
                ->method('getName')
                ->will($this->returnValue('mockItem'));
        $item->expects($this->any())
                ->method('getValue')
                ->will($this->returnValue(
                    $actualItemValue
                ));
        
        $form = new Formagic();
        $form->addItem($item);
        $rendererResult = $this->_renderer->render($form);
        $this->assertEquals($rendererResult['items']['mockItem']['value'], $expectedItemValue);
    }

    public function testRenderRadioInputs()
    {
        $this->markTestIncomplete();
        $containerMock = $this->getMockBuilder('Formagic_Item_Container');
        $form = $this->getMockBuilder('Formagic')->disableOriginalConstructor()->getMock();
        $form->expects($this->once())
            ->method('getItemHolder')
            ->willReturn($containerMock);
        $renderer = new Formagic_Renderer_Array();
    }
}
