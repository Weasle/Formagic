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
 * Formagic Renderer Xhtml
 *
 * @package     Formagic\Renderer
 * @author      Florian Sonnenburg
 * @since       0.2.0 First time introduced
 **/
class Formagic_Renderer_Xhtml extends Formagic_Renderer_Html
{
    /**
     * {@inheritDoc}
     */
    protected $_containerWrapperTemplate = array('' =>
    '
    <fieldset%ATTRIBUTES%>%LABEL%
        <dl>
            %ROWS%
        </dl>
    </fieldset>'
    );

    /**
     * {@inheritDoc}
     */
    protected $_containerLabelTemplate = array('' =>
        '
        <legend>%LABEL%</legend>'
    );

    /**
     * {@inheritDoc}
     */
    protected $_containerRowTemplate = array('' =>
        '
        <dd>
            %CONTAINER%
        </dd>'
    );

    /**
     * {@inheritDoc}
     */
    protected $_itemRowTemplate = array('' =>
        '
        <dt>%LABEL%</dt>
        <dd>%ERROR%%INPUT%</dd>'
    );

    /**
     * Constructor
     * 
     * @param Formagic_Translator_Interface $translator Translator object
     */
    public function  __construct(Formagic_Translator_Interface $translator = null)
    {
        parent::__construct($translator);
    }
}
