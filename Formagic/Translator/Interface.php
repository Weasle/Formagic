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
 * @package     Translator
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2012 Florian Sonnenburg
 * @license     http://www.formagic-php.net/license-agreement/   New BSD License
 */

/**
 * Formagic translator interface
 *
 * @category    Formagic
 * @package     Translator
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2012 Florian Sonnenburg
 * @version     $Id: Interface.php 181 2012-11-02 20:31:51Z meweasle $
 */
interface Formagic_Translator_Interface
{
    /**
     * Translates a string or returns it if no translator is set.
     *
     * @param string $string The string to be translated
     * @param array $arguments Array of arguments to be inserted into the string
     * @return string The translated string
     */
    public function _($string, array $arguments = array());
}
