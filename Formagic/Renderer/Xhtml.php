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
 * @package     Renderer
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2007-2011 Florian Sonnenburg
 * @license     http://www.formagic-php.net/license-agreement/   New BSD License
 */

/**
 * Returns form content as HTML. DIVs and DLs are used for element placing.
 */
require_once 'Html.php';

/**
 * Formagic Renderer Xhtml
 *
 * @category    Formagic
 * @package     Renderer
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2007-2011 Florian Sonnenburg
 * @version     $Id: Xhtml.php 173 2012-05-16 13:19:22Z meweasle $
 **/
class Formagic_Renderer_Xhtml extends Formagic_Renderer_Html
{
    /**
     * Constructor
     * 
     * @param Formagic_Translator $translator Translator object
     */
    public function  __construct(Formagic_Translator $translator = null)
    {
        parent::__construct($translator);
        
        /**
         * Container wrapping template
         **/
        $this->_containerWrapperTemplate = array('' =>
    '<fieldset%ATTRIBUTES%>%LABEL%
        <dl>
            %ROWS%
        </dl>
    </fieldset>'
        );
        
        $this->_containerLabelTemplate = array('' => 
            '
        <legend>%LABEL%</legend>'
        );

        /**
         * Template for rows containing containers
         **/
        $this->_containerRowTemplate = array('' =>
            '
            <dd>
        %CONTAINER%
            </dd>'
        );

        /**
         * Template for rows containing normal items
         **/
        $this->_itemRowTemplate = array('' =>
            '
            <dt>%LABEL%</dt>
            <dd>%ERROR%%INPUT%</dd>'
        );
    }
}
