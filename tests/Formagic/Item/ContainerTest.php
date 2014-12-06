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
 * Tests container item public interface
 *
 * @package     Formagic\Tests
 * @author      Florian Sonnenburg
 **/
class Formagic_Item_Container_Test extends PHPUnit_Framework_TestCase
{
    /**
     * Test setup
     */
    public function setUp()
    {
    }

    /**
     * Checks basic implementation details
     * 
     * @return void
     */
    public function testImplementation()
    {
        $actual = new Formagic_Item_Container('test');
        $this->assertInstanceOf('Formagic_Item_Abstract', $actual);
        $this->assertInstanceOf('IteratorAggregate', $actual);
        $this->assertInstanceOf('Countable', $actual);
    }

    /**
     * Test adding items to container object.
     */
    public function testAddItem()
    {
        $container = new Formagic_Item_Container('test', array(
            'rules' => array('mandatory'),
            'filters' => array('trim')
        ));

        $mockItemName = 'test';
        $mockItemName2 = 'test2';

        // create and add by parameters
        $result = $container->addItem('Mock_MockItem', $mockItemName);
        $this->assertSame($container, $result);

        $addedItem = $container->getItem($mockItemName);
        $this->assertInstanceOf('Formagic_Item_Mock_MockItem', $addedItem);
        $this->assertEquals($addedItem->getName(), $mockItemName);

        // create new object with "new" operator, then add
        $item = new Formagic_Item_Mock_MockItem($mockItemName2);
        $container->addItem($item);
        $addedItem = $container->getItem($mockItemName2);

        $this->assertInstanceOf('Formagic_Item_Mock_MockItem', $item);
        $this->assertSame($item, $addedItem);
    }

    /**
     * Test adding items to container object.
     *
     * @expectedException Formagic_Exception
     */
    public function testAddItemFailure()
    {
        $container = new Formagic_Item_Container('test');
        $container->addItem('Mock_MockItem');
    }

    /**
     * Checks count interface
     */
    public function testCount()
    {
        $container = new Formagic_Item_Container('test');
        $container->addItem('Mock_MockItem', 'i1');
        $container->addItem('Mock_MockItem', 'i2');
        $container->addItem('Mock_MockItem', 'i3');

        $this->assertEquals(3, $container->count());
    }
    
    /**
     * Tests recursive count
     */
    public function testCountRecursive()
    {
        $container = new Formagic_Item_Container('countTest');
        $container->addItem($this->_getContainerWithItems());
        $container->addItem('Mock_MockItem', 'ci2');
        $container->addItem('Mock_MockItem', 'ci3');

        $this->assertEquals(5, $container->count());
    }

    /**
     * Tests iterator interface
     */
    public function testIterator()
    {
        $container = $this->_getContainerWithItems();
        $iterator = $container->getIterator();
        $this->assertInstanceOf('ArrayIterator', $iterator);

        $i = 0;
        foreach($container as $item) {
            $i++;
            $this->assertInstanceOf('Formagic_Item_Mock_MockItem', $item);
        }
        $this->assertEquals(3, $i);
    }

    /**
     * Tests item fetching
     */
    public function testGetItems()
    {
        // test emtpy container
        $container = new Formagic_Item_Container('empty');
        $this->assertEquals(array(), $container->getItems());

        // test container with items
        $subContainer = new Formagic_Item_Container('c1');
        $subContainer->addItem('Mock_MockItem', 'subItem');

        $expected = array(
            'i1' => new Formagic_Item_Mock_MockItem('i1'),
            'i2' => new Formagic_Item_Mock_MockItem('i2'),
            'i3' => new Formagic_Item_Mock_MockItem('i3'),
            'c1' => $subContainer
        );

        $container = new Formagic_Item_Container('test');
        foreach ($expected as $item) {
            $container->addItem($item);
        }

        $this->assertEquals($expected, $container->getItems());
    }

    /**
     * Test item retrieval
     */
    public function testGetItem()
    {
        // test first level items
        $container = new Formagic_Item_Container('testContainer');
        $expected = new Formagic_Item_Mock_MockItem('testItem');
        $actual = $container->addItem($expected)->getItem('testItem');
        $this->assertSame($expected, $actual);

        // test deep level items
        $expected = new Formagic_Item_Mock_MockItem('subItem');
        $subContainer = new Formagic_Item_Container('sub');
        $subContainer->addItem($expected);

        $container->addItem($subContainer);
        $actual = $container->getItem('subItem');
        $this->assertSame($expected, $actual);
    }

    /**
     * Test item retrieval failure
     *
     * @expectedException Formagic_Exception
     */
    public function testGetItemFail()
    {
        $container = new Formagic_Item_Container('testContainer');
        $container->getItem('n/a');
    }
    
    /**
     * Test item retrieval failure for nested containers
     * 
     * @expectedException Formagic_Exception
     */
    public function testGetDeepLevelItemFail()
    {
        // test first level items
        $container = new Formagic_Item_Container('testContainer');
        $subContainer = new Formagic_Item_Container('sub');

        $container->addItem($subContainer);
        $container->getItem('itemDoesNotExist');
    }

    /**
     * Test that Container returns NULL if exceptions are suppressed
     */
    public function testGetItemFailNoException()
    {
        $container = new Formagic_Item_Container('testContainer');
        $actual = $container->getItem('n/a', false);
        $this->assertNull($actual);
    }
    
    /**
     * Test setting values to container sub-items
     */
    public function testSetValue()
    {
        // set default
        $container = $this->_getContainerWithItems();
        $expected = array(
            'i1'      => 'v1',
            'i2'      => 'v2',
            'i3'      => 'v3',
        );
        $container->setValue($expected);
        $actual = array(
            $container->getItem('i1')->getName() => $container->getItem('i1')->getValue(),
            $container->getItem('i2')->getName() => $container->getItem('i2')->getValue(),
            $container->getItem('i3')->getName() => $container->getItem('i3')->getValue(),
        );
        $this->assertEquals($expected, $actual);

        // set sub-container value
        $subItem = new Formagic_Item_Mock_MockItem('subItem');
        $subContainer = new Formagic_Item_Container('subContainer');
        $subContainer->addItem($subItem);
        $container->addItem($subContainer);

        $expected['subItem'] = 'sv1';
        $container->setValue($expected);

        $key = $container->getItem('subContainer')->getItem('subItem')->getName();
        $val = $container->getItem('subContainer')->getItem('subItem')->getValue();
        $actual[$key] = $val;

        $this->assertEquals($expected, $actual);

        // set value for image submit
        $values = $expected;
        $container->addItem('ImageSubmit', 'imgSubmit', array('label' => 'test'));
        $values['imgSubmit_x'] = '1';
        $values['imgSubmit_y'] = '1';
        $container->setValue($values);

        $key = $container->getItem('imgSubmit')->getName();
        $val = $container->getItem('imgSubmit')->getValue();
        $actual[$key] = $val;

        $expected['imgSubmit'] = 'test';
        $this->assertEquals($expected, $actual);

        $expectedCoordinates = array('x' => 1, 'y' => 1);
        $actualCoordinates = $container->getItem('imgSubmit')->getClickCoordinates();
        $this->assertInternalType('integer', $actualCoordinates['x']);
        $this->assertInternalType('integer', $actualCoordinates['y']);
        $this->assertEquals($expectedCoordinates, $actualCoordinates);
        
        // test skipping clearing value
        $container = new Formagic_Item_Container('test');
        $testItem  = new Formagic_Item_Mock_MockItem('testMock', array(
            'value' => 0));
        $container->addItem($testItem);
        $container->setValue(array());
        $actualValue = $testItem->getValue();
        $this->assertNull($actualValue);
    }
    
    /**
     * Test that container only accepts arrays as values
     * 
     * @expectedException Formagic_Exception
     */
    public function testSetValuesException()
    {
        $container = new Formagic_Item_Container('test');
        $container->setValue('test');
    }

    /**
     * Test adding rules to all sub-items
     */
    public function testAddRule()
    {
        $rule = new Formagic_Rule_Mock_MockRule();
        $container = $this->_getContainerWithItems();
        $container->addRule($rule);
        foreach ($container as $item) {
            $this->assertTrue($item->hasRule('Mock_MockRule'));
        }
    }

    /**
     * Test adding filters to all sub-items
     */
    public function testAddFilter()
    {
        $filter = new Formagic_Filter_Mock_MockFilter();
        $container = $this->_getContainerWithItems();
        $container->addFilter($filter);
        foreach ($container as $item) {
            $this->assertTrue($item->hasFilter('Mock_MockFilter'));
        }
    }

    /**
     * Test adding items, adding rules and filters
     */
    public function testDefaultRulesAndFilters()
    {
        $container = new Formagic_Item_Container('test', array(
            'rules' => array('Mock_MockRule'),
            'filters' => array('Mock_MockFilter')
        ));
        $container->addItem('Mock_MockItem', 'i1');
        $item = $container->getItem('i1');
        $this->assertTrue($item->hasRule('Mock_MockRule'));
        $this->assertTrue($item->hasFilter('Mock_MockFilter'));

        $item = new Formagic_Item_Mock_MockItem('i2');
        $container->addItem($item);
        $this->assertTrue($item->hasRule('Mock_MockRule'));
        $this->assertTrue($item->hasFilter('Mock_MockFilter'));
    }

    /**
     * Test readonly attribute for sub-items
     */
    public function testSetReadonly()
    {
        $container = $this->_getContainerWithItems();
        $container->setReadonly(true);
        $container->addItem('Mock_MockItem', 'testMock');
        foreach($container as $item) {
            $html = $item->getHtml();
            $this->assertEquals(
                Formagic_Item_Mock_MockItem::HTML_OUTPUT_READONLY,
                $html
            );
        }
    }

    /**
     * Test hidden attribute for sub-items
     */
    public function testSetHidden()
    {
        $container = $this->_getContainerWithItems();
        $container->setHidden(true);
        $container->addItem('Mock_MockItem', 'testMock');
        foreach($container as $item) {
            $this->assertTrue($item->isHidden());
        }
    }

    /**
     * Test disabled attribute for sub-items
     */
    public function testSetDisabled()
    {
        $container = $this->_getContainerWithItems();
        $container->setDisabled(true);
        $container->addItem('Mock_MockItem', 'testMock');
        foreach($container as $item) {
            $this->assertTrue($item->isDisabled());
        }
    }

    /**
     * Test getting values from container
     */
    public function testGetValue()
    {
        //test default
        $container = $this->_getContainerWithItems();
        $actual   = $container->getValue();
        $this->assertInternalType('array', $actual);

        $expected = array(
            'i1' => 'v1',
            'i2' => 'v2',
            'i3' => 'v3'
        );
        $this->assertSame($expected, $actual);

        // test ignored item
        $container->getItem('i2')->setIgnore(true);
        $actual = $container->getValue();

        $expected = array(
            'i1' => 'v1',
            'i3' => 'v3'
        );
        $this->assertSame($expected, $actual);

        // test subcontainer
        $subContainer = new Formagic_Item_Container('sub');
        $subContainer->addItem('Mock_MockItem', 'si1', array('value' => 'sv1'));
        $container->addItem($subContainer);
        $actual = $container->getValue();

        $expected = array(
            'i1' => 'v1',
            'i3' => 'v3',
            'si1' => 'sv1'
        );
        $this->assertSame($expected, $actual);
    }

    /**
     * Test container item validation
     */
    public function testValidate()
    {
        $container = $this->_getContainerWithItems();
        foreach($container as $item) {
            $item->setValue('1');
            $item->addRule('Mock_MockRule');
        }
        $validated = $container->validate();
        $this->assertTrue($validated);

        $container->getItem('i1')->setValue('0');
        $validated = $container->validate();
        $this->assertFalse($validated);

        $container->getItem('i1')->setValue('1');
        $container->getItem('i2')->setValue('0');
        $validated = $container->validate();
        $this->assertFalse($validated);

        $container->getItem('i2')->setValue('1');
        $container->getItem('i3')->setValue('0');
        $validated = $container->validate();
        $this->assertFalse($validated);
    }

    /**
     * @throws Formagic_Exception
     */
    public function testValidateDisabledItem()
    {
        $container = new Formagic_Item_Container('testItem');

        $item = $this->getMockForAbstractClass('Formagic_Item_Abstract', array('testItem'));
        $item->setDisabled(true);
        $item
            ->expects($this->never())
            ->method('validate');

        $rule = $this->getMockForAbstractClass('Formagic_Rule_Abstract', array(), '', false);
        $rule
            ->expects($this->never())
            ->method('validate');

        $container
            ->addItem($item)
            ->addRule($rule)
            ->validate();
    }

    /**
     * Returns pre-defined items
     *
     * @return array of Formagic_Item_MockItem
     */
    private function _getItems()
    {
        $items = array(
            'i1' => new Formagic_Item_Mock_MockItem('i1', array('value' => 'v1')),
            'i2' => new Formagic_Item_Mock_MockItem('i2', array('value' => 'v2')),
            'i3' => new Formagic_Item_Mock_MockItem('i3', array('value' => 'v3'))
        );
        return $items;
    }

    /**
     * Returns pre-defined container
     * 
     * @param Formagic_Item_Abstract[] $items
     * @return Formagic_Item_Container
     */
    private function _getContainerWithItems(array $items = null)
    {
        if (!$items) {
            $items = $this->_getItems();
        }
        $container = new Formagic_Item_Container('test');
        foreach ($items as $item) {
            $container->addItem($item);
        }

        return $container;
    }
}
