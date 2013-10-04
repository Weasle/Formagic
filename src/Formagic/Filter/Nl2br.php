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
 * @copyright   Copyright (c) 2007-2013 Florian Sonnenburg
 * @license     http://www.formagic-php.net/license-agreement/   New BSD License
 */

/**
 * Converts all newline characters to HTML breaks
 *
 * @category    Formagic
 * @package     Filter
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2009 Florian Sonnenburg
 **/
class Formagic_Filter_Nl2br implements Formagic_Filter_Interface
{
    /**
     * Transforms all newline characters to HTML breaks.
     *
     * Utilizes PHP's {@link nl2br() nl2br()}.
     * 
     * @param string $value The string to be filtered
     * @return string The filtered string
     */
    public function filter($value)
    {
        return nl2br($value);
    }
}