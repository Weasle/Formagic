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
 * @package     Generator
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2007-2012 Florian Sonnenburg
 * @license     http://www.formagic-php.net/license-agreement/   New BSD License
 */
namespace Formagic\Generator\InjectionContainer;

use Formagic\Generator\CodeFormatter\FormFormatter;
use Formagic\Generator\CodeFormatter\ItemFormatter;
use Formagic\Generator\Writer\ResourceRegistry;

/**
 * Dependencies for Formagic code generator
 *
 * @category    Formagic
 * @package     Generator
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2012 Florian Sonnenburg
 * @version     $Id: Container.php 183 2012-11-17 13:34:27Z meweasle $
 */
class Container extends \Pimple
{
    /**
     * Dependency injection definition
     */
    public function __construct()
    {
        $this['itemFormatter'] = $this->share(function ($c) {
            return new ItemFormatter($c['resourceRegistry']);
        });

        $this['resourceRegistry'] = $this->share(function ($c) {
            return new ResourceRegistry();
        });

        $this['formFormatter'] = $this->share(function ($c) {
            return new FormFormatter($c['itemFormatter'], $c['resourceRegistry']);
        });
    }
}
