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
 * @author    Florian Sonnenburg
 * @copyright 2007-2014 Florian Sonnenburg
 * @license   http://www.formagic-php.net/license-agreement/   New BSD License
 */

/**
 * Defines interface that all Formagic renderers have to implement.
 *
 * @package     Formagic\Renderer
 * @author      Florian Sonnenburg
 * @since       1.0.0 First time introduced
 **/
interface Formagic_Renderer_Interface
{
    /**
     * Renders form item information into a specific data format.
     *
     * @param Formagic $form The formagic object to be rendered.
     * @return mixed The assembled form representation.
     **/
    public function render(Formagic $form);
}