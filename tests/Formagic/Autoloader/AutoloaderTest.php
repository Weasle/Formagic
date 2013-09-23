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
 * Tests for Formagic autoloader
 *
 * @category    Formagic
 * @package     Tests
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2013
 * @version     $Id: FormagicTest.php 173 2012-05-16 13:19:22Z meweasle $
 **/
class Formagic_Autoloader_Test extends PHPUnit_Framework_TestCase
{

    /**
     */
    public function testAddBaseDirByMethod()
    {
        $this->markTestIncomplete();
        $newBaseDir = realpath('.');

        // setting explicitly
        $formagic = new Formagic();
        $formagic->addBaseDir($newBaseDir);
        $actual = $formagic->getBaseDirs();
        $this->assertContains($newBaseDir, $actual);
    }

    /**
     */
    public function testAddBaseDuplicateDir()
    {
        $this->markTestIncomplete();
        $newBaseDir = realpath('.');

        $formagic = new Formagic(array(
            'pluginBaseDir' => $newBaseDir
        ));
        $actual = Formagic::getBaseDirs();
        $this->assertContains($newBaseDir, $actual);

        Formagic::addBaseDir($newBaseDir);
        $actual2 = Formagic::getBaseDirs();
        $this->assertContains($newBaseDir, $actual2);
        $this->assertEquals($actual, $actual2);
    }

    /**
     */
    public function testAddMultipleBaseDirs()
    {
        $this->markTestIncomplete();
        $newBaseDir = realpath('.');
        $newBaseDirNA = realpath('./notExists');
        $newBaseDir2 = realpath('./MockClasses');

        $formagic = new Formagic();
        Formagic::addBaseDir($newBaseDir);
        Formagic::addBaseDir($newBaseDirNA);
        Formagic::addBaseDir($newBaseDir2);
        $actual = Formagic::getBaseDirs();
        $this->assertContains($newBaseDir, $actual);
        $this->assertNotContains($newBaseDirNA, $actual);
        $this->assertContains($newBaseDir2, $actual);
    }

    /**
     */
    public function testLoadClass()
    {
        $this->markTestIncomplete();
        $className = 'Formagic_Item_MockItem';
        $newBaseDir = realpath('./MockClasses');
        Formagic::addBaseDir($newBaseDir);
        Formagic::loadClass($className);
        $this->assertEquals(true, class_exists($className));
        
        $className = 'Formagic_Translator';
        Formagic::loadClass($className);
        $this->assertEquals(true, class_exists($className));
    }

    /**
     * @expectedException Formagic_Exception
     */
    public function testLoadClassFail()
    {
        $this->markTestIncomplete();
        $className = 'Formagic_ClassNotExists';
        $newBaseDir = realpath('.');
        Formagic::addBaseDir($newBaseDir);
        Formagic::loadClass($className);
    }

    /**
     */
    public function testLoadClassFromMultipleBaseDirs()
    {
        $this->markTestIncomplete();
        $className = 'Formagic_Item_MockItem';
        $newBaseDirFake = realpath('.');
        $newBaseDirReal = realpath('./MockClasses');
        Formagic::addBaseDir($newBaseDirFake);
        Formagic::addBaseDir($newBaseDirReal);
        Formagic::loadClass($className);
        $this->assertEquals(true, class_exists($className));
    }
}
