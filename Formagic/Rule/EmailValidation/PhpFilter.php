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
 * @subpackage  EmailValidation
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2007-2011 Florian Sonnenburg
 * @license     http://www.formagic-php.net/license-agreement/   New BSD License
 */

/**
 * Mail validation strategy interface
 */
require_once('Interface.php');

/**
 * Implementation of validation strategy interface.
 *
 * @category    Formagic
 * @package     Rule
 * @subpackage  EmailValidation
 * @author      Marc Schrader
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2007-2011 Florian Sonnenburg
 * @version     $Id: $
 **/
class Formagic_Rule_EmailValidation_PhpFilter implements Formagic_Rule_EmailValidation_Interface
{
    /**
     * Checks an email address by regex.
     * 
     * @param string $mailAddress
     * @return boolean 
     */
    public function isValidEmailAddress($mailAddress)
    {
        $result = true;
        if(false === filter_var($mailAddress, FILTER_VALIDATE_EMAIL)) {
            $result = false;
        }
        return $result;
    }
}