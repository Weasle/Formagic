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
 * Filters all prepended and trailing whitespaces from given value.
 *
 * @package     Formagic\Filter
 * @author      Florian Sonnenburg
 * @since       2011 First time introduced
 **/
class Formagic_Filter_Trim implements Formagic_Filter_Interface
{
    /**
     * Removes all prepended and trailing whitespaces.
     *
     * @param mixed $value Value to be trimmed
     * @return mixed Trimmed value
     */
    public function filter($value)
    {
        return trim($value);
    }
}
