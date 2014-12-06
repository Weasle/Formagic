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
 * Tests Formagic public interface
 *
 * @package     Formagic\Tests
 * @author      Florian Sonnenburg
 **/
class Formagic_Test extends PHPUnit_Framework_TestCase
{
    /**
     * Test item creation by Formagic item factory
     */
    public function testCreateItem()
    {
        $newItem = Formagic::createItem('text', 'test');
        $this->assertInstanceOf('Formagic_Item_Abstract', $newItem);
        $this->assertEquals($newItem->getName(), 'test');
    }

    /**
     * Tests if correct exception is thrown if item class does not exist
     *
     * @expectedException Formagic_Exception
     */
    public function testCreateItemException()
    {
        Formagic::createItem('n/a', 'n/a');
    }
    
    /**
     * Test that setting wrong options throws an exception
     * 
     * @expectedException Formagic_Exception
     */
    public function testSetWrongOption()
    {
        $formagic = new Formagic(array('n/a' => 'n/a'));
    }
    
    /**
     * Tests that getInfo() can be called
     */
    public function testGetInfo()
    {
        $formagic = new Formagic();
        $actual = $formagic->getInfo();
        $this->assertInternalType('string', $actual);
    }

    /**
     * Tests getName()
     */
    public function testGetNameForExplicitlySetName()
    {
        $name = 'testName';
        $formagic = new Formagic();
        $formagic->setName($name);
        $actual = $formagic->getName();

        $this->assertEquals($name, $actual);
    }
    
    /**
     * Tests getName()
     */
    public function testGetNameForSetNameByOption()
    {
        $name = 'testName';
        $formagic = new Formagic(array('name' => $name));
        $actual = $formagic->getName();

        $this->assertEquals($name, $actual);
    }

    /**
     * Tests getName()
     */
    public function testGetNameForOverwrittenName()
    {
        $name = 'testName';
        $formagic = new Formagic(array('name' => 'firstName'));
        $formagic->setName($name);
        $actual = $formagic->getName();

        $this->assertEquals($name, $actual);
    }

    /**
     * Tests that a rule can be set by option
     */
    public function testSetRulesByOption()
    {
        $mockRule = $this->getMock('Formagic_Rule_Abstract');
        $formagic = new Formagic(array(
            'rules' => $mockRule
        ));
        $formagic->getItemHolder()->hasRule('Abstract');
    }
    
    /**
     * Tests that a filter can be set by option
     */
    public function testSetFiltersByOption()
    {
        $mockFilter = $this->getMock('Formagic_Filter_Interface');
        $formagic = new Formagic(array(
            'filters' => $mockFilter
        ));
        $formagic->getItemHolder()->hasFilter('Abstract');
    }

    /**
     * Test first level item holder
     */
    public function testItemHolder()
    {
        $formagic = new Formagic();
        $itemHolder = $formagic->getItemHolder();
        $this->assertInstanceOf('Formagic_Item_Container', $itemHolder);

        $formagic->addItem('input', 'test');
        $item = $formagic->getItemHolder()->getItem('test');

    }

    /**
     * Test adding items to Formagic object.
     */
    public function testAddItem()
    {
        // create and add by parameters
        $formagic = new Formagic();
        $result = $formagic->addItem('input', 'test');
        $this->assertInstanceOf('Formagic_Item_Container', $result);

        // get item by magic Formagic::__get()
        $mockItemName2 = 'test2';

        $formagic->addItem('Mock_MockItem', $mockItemName2);
        $__getItem = $formagic->test2;
        $this->assertInstanceOf('Formagic_Item_Mock_MockItem', $__getItem);

        $addedItem = $formagic->getItem($mockItemName2);
        $this->assertSame($addedItem, $__getItem);
        $this->assertEquals($__getItem->getName(), $mockItemName2);

    }

    /**
     * Tests for exception if addItem is called without name
     * @expectedException Formagic_Exception
     */
    public function testAddItemWithoutNameException()
    {
        $formagic = new Formagic();
        $formagic->addItem('input');
    }

    /**
     * Tests for exception if item cannot be loaded
     * @expectedException Formagic_Exception
     */
    public function testAddWrongItemException()
    {
        $formagic = new Formagic();
        $formagic->addItem('n/A');
    }

    /**
     * Test setting submit method
     */
    public function testSetMethod()
    {
        // explicit setting
        $methods = array('post', 'get');
        foreach($methods as $method) {
            $formagic = new Formagic();
            $actualMethod = $formagic->setMethod($method)->getMethod();
            $this->assertEquals($method, $actualMethod);

            $html = $formagic->render();
            $matcher = array(
                'tag'        => 'form',
                'attributes' => array('method' => $method)
            );
            $this->assertTag($matcher, $html);
        }

        // setting by option
        $method = 'post';
        $formagic = new Formagic(array('method' => $method));
        $actualMethod = $formagic->getMethod();
        $this->assertEquals($method, $actualMethod);

        $html = $formagic->render();
        $matcher = array(
            'tag'        => 'form',
            'attributes' => array('method' => $method)
        );
        $this->assertTag($matcher, $html);
    }

    /**
     * Test setting non-supported submit method.
     *
     * Should throw Formagic_Exception
     *
     * @expectedException Formagic_Exception
     */
    public function testSetWrongMethod()
    {
        $formagic = new Formagic();
        $actualMethod = $formagic->setMethod('n/a');
    }

    /**
     * Test setting a custom attribute to the form tag.
     */
    public function testSetAttribute()
    {
        // set explicitly
        $expectedAttr = 'class';
        $expectedValue = 'testClass';

        $formagic = new Formagic();
        $formagic->setAttributes(array($expectedAttr => $expectedValue));

        $html = $formagic->render();
        $matcher = array(
            'tag'        => 'form',
            'attributes' => array($expectedAttr => $expectedValue)
        );
        $this->assertTag($matcher, $html);
        
        // set by option
        $formagic = new Formagic(array(
            'attributes' => array($expectedAttr => $expectedValue)
        ));

        $html = $formagic->render();
        $matcher = array(
            'tag'        => 'form',
            'attributes' => array($expectedAttr => $expectedValue)
        );
        $this->assertTag($matcher, $html);
    }

    /**
     * Test setting form name
     */
    public function testFormName()
    {
        //set by option
        $name = 'testname_2';
        $expectedId = 'testname-2';

        // name and ID differ
        $formagic = new Formagic(array('name' => $name));
        $this->assertEquals($name, $formagic->getAttribute('name'));
        $this->assertEquals($expectedId, $formagic->getAttribute('id'));
    }

    /**
     * Test submission tracking
     */
    public function testSubmissionTracking()
    {
        // set explicitly
        $formagic = new Formagic();

        // test default status (should be TRUE)
        $tsStatus = $formagic->getTrackSubmission();
        $this->assertInternalType('boolean', $tsStatus);
        $this->assertEquals(true, $tsStatus);

        // set new status
        $newStatus = false;
        $formagic->setTrackSubmission($newStatus);
        $tsStatus = $formagic->getTrackSubmission();
        
        $this->assertInternalType('boolean', $tsStatus);
        $this->assertEquals($newStatus, $tsStatus);

        // test bool casting
        $formagic->setTrackSubmission('string');
        $this->assertInternalType('boolean', $formagic->getTrackSubmission());

        // set by options
        $newStatus = false;
        $formagic = new Formagic(array('trackSubmission' => $newStatus));
        $tsStatus = $formagic->getTrackSubmission();
        $this->assertEquals($newStatus, $tsStatus);
    }

    /**
     * Test form action setting
     */
    public function testFormAction()
    {
        // set explicitly
        $value = 'test';
        $formagic = new Formagic();
        $formagic->setFormAction($value);
        $expected = $formagic->getFormAction();

        $this->assertEquals($expected, $value);

        // set as option
        $value = 'test2';
        $formagic = new Formagic(array('action' => $value));
        $expected = $formagic->getFormAction();

        $this->assertEquals($expected, $value);
    }

    /**
     * Action can only be string
     *
     * @expectedException Formagic_Exception
     */
    public function testFormActionException()
    {
        $formagic = new Formagic();
        $formagic->setFormAction(true);
    }

    /**
     * Test if the same values are returned from Formagic and itemHolder
     */
    public function testGetValues()
    {
        $values = array('text1' => 'test');
        $formagic = new Formagic();
        $formagic->addItem('text', 'text1');
        $formagic->setValues($values);

        $itemHolder = $formagic->getItemHolder();
        $expected   = $itemHolder->getValue();
        $actual     = $formagic->getValues();

        $this->assertEquals($expected, $actual);
    }
    
    /**
     * Test if the same values are returned from Formagic and itemHolder
     */
    public function testGetRaw()
    {
        $_GET = array('text1' => 'test');
        $formagic = new Formagic(array('method' => 'get'));
        $actual = $formagic->getRaw();
        $this->assertEquals($_GET, $actual);
    }
    
    /**
     * Test that isSubmitted returns valid results
     */
    public function testIsSubmittedWithSubmissionTracking()
    {
        $name = 'test';
        $_GET = array('fm_ts__' . $name => 'submitted');
        $formagic = new Formagic(array(
            'method' => 'get',
            'name'   => $name
        ));
        $actual = $formagic->isSubmitted();
        $this->assertTrue($actual);
    }
    
    /**
     * Test that isSubmitted returns valid results
     */
    public function testIsSubmittedFalseWithoutSubmissionTracking()
    {
        $name = 'test';
        $formagic = new Formagic(array(
            'trackSubmission' => false,
            'name' => $name
        ));
        $actual = $formagic->isSubmitted();
        $this->assertFalse($actual);
    }
    
    /**
     * Test that isSubmitted returns valid results
     */
    public function testIsSubmittedTrueWithoutSubmissionTracking()
    {
        $name = 'test';
        $_GET = array('someKey' => 'someValue');
        $formagic = new Formagic(array(
            'trackSubmission' => false,
            'name'   => $name,
            'method' => 'get'));
        $actual = $formagic->isSubmitted();
        $this->assertTrue($actual);
    }

    /**
     * Tests if item value change is executed after setting and getting of a
     * first value.
     */
    public function testSetValuesSubsequent()
    {
        $form = new Formagic();

        // create item and set value afterwards
        $item = $form->createItem('input', 'in');
        $form->addItem($item);

        $form->setValues(array('in' => 'test'));
        $this->assertEquals($item->getValue(), 'test');

        $item->setValue('test2');
        $this->assertEquals($item->getValue(), 'test2');


        // create / add / setValue item in one step, set value afterwards
        $testValue = 'testValue2';
        $form->addItem('text', 'in2', array('value' => $testValue));
        $item = $form->getItem('in2');

        $this->assertEquals($item->getValue(), $testValue);

        $testValue3 = 'testValue3';
        $form->setValues(array('in2' => $testValue3));
        $this->assertEquals($item->getValue(), $testValue3);

        $testValue4 = 'testValue4';
        $item->setValue($testValue4);
        $this->assertEquals($item->getValue(), $testValue4);
    }

    public function testValidate()
    {
        $formagic = new Formagic();
        $validated = $formagic->validate();
        $this->assertInternalType('boolean', $validated);
        $this->assertEquals(false, $validated);

        $fmTsPrefix = 'fm_ts__';
        $_POST = array(
            $fmTsPrefix . 'formagic' =>  1,
        );
        $formName   = 'formname';
        $_GET = array(
            $fmTsPrefix . $formName  =>  1,
        );

        $formagic = new Formagic();
        $validated = $formagic->validate();
        $this->assertEquals(true, $validated);

        $formagic = new Formagic(array('method' => 'get', 'name' => $formName));
        $validated = $formagic->validate();
        $this->assertEquals(true, $validated);
    }

    /**
     *
     */
    public function testSetRenderer()
    {
        $rendererObj = $this->getMock('Formagic_Renderer_Interface');

        // set by option by string
        $formagic = new Formagic(array('renderer' => 'Html'));
        $renderer = $formagic->getRenderer();
        $this->assertInstanceOf('Formagic_Renderer_Interface', $renderer);

        // set by option by object
        $formagic = new Formagic(array('renderer' => $rendererObj));
        $renderer = $formagic->getRenderer();
        $this->assertInstanceOf('Formagic_Renderer_Interface', $rendererObj);
        $this->assertSame($rendererObj, $renderer);

        // set by method by string
        $rendererDef = 'Html';
        $formagic = new Formagic();
        $formagic->setRenderer($rendererDef);
        $renderer = $formagic->getRenderer();
        $this->assertInstanceOf('Formagic_Renderer_Html', $renderer);

        // set by method by object
        $formagic = new Formagic();
        $formagic->setRenderer($rendererObj);
        $renderer = $formagic->getRenderer();
        $this->assertInstanceOf('Formagic_Renderer_Interface', $rendererObj);
        $this->assertSame($rendererObj, $renderer);
    }

    /**
     * Test that an exception is thrown if setRenderer() recieves wrong
     * argument type
     * 
     * @expectedException Formagic_Exception
     */
    public function testSetRendererInvalidTypeException()
    {
        $formagic = new Formagic();
        $formagic->setRenderer(array());
    }
    
    /**
     * Test that an exception is thrown if renderer class not found
     *
     * @expectedException Formagic_Exception
     */
    public function testSetRendererMissingClassException()
    {
        $formagic = new Formagic();
        $formagic->setRenderer('n_a');
    }

    /**
     * Test that an exception is thrown if renderer class is no instance of Formagic_Renderer_Interface
     *
     * @expectedException Formagic_Exception
     */
    public function testSetRendererInvalidClassException()
    {
        $formagic = new Formagic();
        $formagic->setRenderer('Mock_NoRenderer');
    }
    
    /**
     * Tests Formagic __toString() method
     */
    public function testMagicToString()
    {
        $formagic    = new Formagic();
        $toStringStr = $formagic->__toString();
        $renderStr   = $formagic->render();
        $this->assertEquals($toStringStr, $renderStr);
    }
    
    /**
     * Test that Formagic throws an exception
     * 
     * @expectedException Formagic_Exception
     */
    public function testMagicCallFail()
    {
        $formagic = new Formagic();
        $actual = $formagic->methodDoesNotExist();
    }
    
    /**
     * Test that __set throws an exception
     * 
     * @expectedException Formagic_Exception
     */
    public function testMagicSet()
    {
        $formagic = new Formagic();
        $formagic->propertyDoesNotExist = 'doesNotExist';
    }
}
