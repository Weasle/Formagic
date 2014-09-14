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
 * Protects a form from cross site request forgery attacks.
 *
 * @package     Formagic\Item
 * @author      Florian Sonnenburg
 * @since       1.1.0 First time introduced
 */
class Formagic_Item_XsrfProtection extends Formagic_Item_Hidden
{
    /**
     * Session object
     * @var Formagic_Session_Interface
     */
    protected $_session;

    /**
     * Flags session rule added
     * @var boolean
     */
    private $_ruleAdded = false;

    /**
     * Item type
     * @var string
     */
    protected $type = 'hidden_xsrfProtection';

    /**
     * Initialize form object
     *
     * @param array $additionalArgs
     * @throws Formagic_Exception
     */
    protected function _init($additionalArgs)
    {
        if (!empty($additionalArgs['session'])) {
            $this->setSession($additionalArgs['session']);
        }
        $this->_isHidden = true;
    }

    /**
     * Validates against session
     *
     * @return boolean
     */
    public function validate()
    {
        if (!$this->_ruleAdded) {
            $this->addRule('SessionValue', array(
                'session' => $this->getSession(),
                'sessionKey' => $this->getName(),
                'message' => 'Token validation failed'
            ));
            $this->_ruleAdded = true;
        }
        return parent::validate();
    }

    /**
     * Resets XSRF token and returns field HTML
     *
     * @return string Field HTML
     */
    public function getHtml()
    {
        $this->_initToken();
        return parent::getHtml();
    }

    /**
     * Sets session object
     *
     * @param Formagic_Session_Interface $session
     * @return \Formagic_Item_XsrfProtection
     */
    public function setSession(Formagic_Session_Interface $session)
    {
        $this->_session = $session;
        return $this;
    }

    /**
     * Returns session object
     *
     * @return \Formagic_Session_Interface Session object
     */
    public function getSession()
    {
        if (null === $this->_session) {
            $sessionNamespace = '__fm__' . $this->_name;
            $this->_session = new Formagic_Session_Php($sessionNamespace);
        }
        return $this->_session;
    }

    /**
     * Generates new token and saves it to field value and session.
     *
     * @return void
     */
    protected function _initToken()
    {
        $session = $this->getSession();
        $sessionKey = $this->getName();
        $newToken = md5(uniqid());
        $this->setValue($newToken);
        $session->set($sessionKey, $newToken);
    }
}
