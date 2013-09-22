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
 * @package     Rule
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2007-2009 Florian Sonnenburg
 * @license     http://www.formagic-php.net/license-agreement/   New BSD License
 */

/**
 * Checks if value is given
 *
 * @category    Formagic
 * @package     Rule
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2007-2009 Florian Sonnenburg
 * @license     http://www.formagic-php.net/license-agreement/   New BSD License
 * @version     $Id: Mandatory.php 173 2012-05-16 13:19:22Z meweasle $
 **/
class Formagic_Rule_Mandatory extends Formagic_Rule_Abstract
{
    /**
     * Default message
     * @var string
     **/
    protected $_message = 'Please enter a value';

    /**
     * Performs rule check
     *
     * Mandatory rule checks if value is given.
     *
     * @param string $value Item value to be checked
     * @return boolean True if the item has a value
     **/
    public function validate($value)
    {
        if (empty($value)) {
            return false;
        }
        return true;
    }

}