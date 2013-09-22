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
 * @package     Filter
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2009 Florian Sonnenburg
 * @license     http://www.formagic-php.net/license-agreement/   New BSD License
 */

/**
 * Accepts a key-value array of replacements, the key beeing the value to be
 * replaced, the value beeing the replacement.
 *
 * @category    Formagic
 * @package     Filter
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2009 Florian Sonnenburg
 * @version     $Id: Replace.php 173 2012-05-16 13:19:22Z meweasle $
 **/
class Formagic_Filter_Replace implements Formagic_Filter_Interface
{
    /**
     * Array containing the replacements
     *
     * @var array
     */
    protected $_replacements;

    /**
     * Constructor
     *
     * @param array $replacements Find-Replace-Array
     */
    public function __construct(array $replacements)
    {
        $this->_replacements = $replacements;
    }

    /**
     * Replaces strings according to the replacements array.
     *
     * Uses the PHP function {@link strtr() strtr()} for replacing.
     *
     * @param mixed $value The value to be filtered
     * @return mixed The filtered value
     */
    public function filter($value)
    {
        return strtr($value, $this->_replacements);
    }
}