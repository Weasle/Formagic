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
 **/
class Formagic_Autoloader_AutoloaderTest extends PHPUnit_Framework_TestCase
{
    /**
     * Tests that loading a class from a baseDir works
     */
    public function testAddBaseDirByConstructor()
    {
        $baseDir = dirname(__FILE__);
        $newBaseDirs = array($baseDir);

        $subject = new Formagic_Autoloader($newBaseDirs);
        $baseDirs = $subject->getBaseDirs();
        $this->assertContains($baseDir, $baseDirs);
    }

    /**
     * Tests that loading a class from a baseDir works
     */
    public function testAddBaseDirByMethod()
    {
        $baseDir = dirname(__FILE__);

        $subject = new Formagic_Autoloader();
        $baseDirs = $subject->getBaseDirs();
        $this->assertNotContains($baseDir, $baseDirs);

        $subject->addBaseDir($baseDir);
        $baseDirs = $subject->getBaseDirs();
        $this->assertContains($baseDir, $baseDirs);
    }

    /**
     * Tests that loading a class that is already loaded will run without failures
     */
    public function testClassAlreadyLoaded()
    {
        $newBaseDirs = array(dirname(dirname(__FILE__)));

        $subject = new Formagic_Autoloader($newBaseDirs);
        $subject->loadClass('Formagic_Autoloader_TestClass');
        $subject->loadClass('Formagic_Autoloader_TestClass');
        $classExists = class_exists('Formagic_Autoloader_TestClass', false);
        $this->assertTrue($classExists);
    }
}
