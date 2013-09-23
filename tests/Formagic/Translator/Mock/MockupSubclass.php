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
 * @subpackage  Mockups
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2007-2011 Florian Sonnenburg
 * @license     http://www.formagic-php.net/license-agreement/   New BSD License
 */

/**
 * Translator mock
 *
 * @category    Formagic
 * @package     Tests
 * @subpackage  Mockups
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2010
 * @version     $Id: MockupSubclass.php 173 2012-05-16 13:19:22Z meweasle $
 **/
class Formagic_Translator_Mock_MockupSubclass extends Formagic_Translator
{
    /**
     * Mockup of default translation method
     *
     * @param string $value Translate value
     * @return string Concat'd string
     */
    public function _($value)
    {
        return 'UnitTestMockup' . $value;
    }
}