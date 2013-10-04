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
 * @category  Formagic
 * @package   Autoloader
 * @author    Florian Sonnenburg
 * @copyright Copyright (c) 2008-2013 Florian Sonnenburg
 * @license   http://www.formagic-php.net/license-agreement/   New BSD License
 */

/**
 * Autoloader implementation for Formagic classes.
 *
 * @category    Formagic
 * @package     Autoloader
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2008-2013 Florian Sonnenburg
 **/
class Formagic_Autoloader
{
    /**
     * Directories to load classes from
     * @var array
     */
    private $baseDirs;

    /**
     * Static autoloader instance
     * @var Formagic_Autoloader
     */
    private static $instance;

    /**
     * @param array $baseDirs Array of directories to load classes from
     */
    public function __construct(array $baseDirs = array())
    {
        $formagicDir = dirname(dirname(__FILE__));
        array_unshift($baseDirs, $formagicDir);
        $this->baseDirs = $baseDirs;
    }

    /**
     * Registers this instance as an autoloader.
     *
     * @param array $baseDirs Array of directories to load classes from
     * @param boolean $prepend Whether to prepend the autoloader or not
     *
     * @return Formagic_Autoloader
     *
     * @codeCoverageIgnore
     */
    public static function register(array $baseDirs = array(), $prepend = false)
    {
        self::$instance = new Formagic_Autoloader($baseDirs);
        spl_autoload_register(array(self::$instance, 'loadClass'), true, $prepend);

        return self::$instance;
    }

    /**
     * Adds a directory to base dir stack (globally for all Formagic instances).
     *
     * Base directory of custom Formagic extension classes. It is assumed that
     * a standard directory structure, similar to the Formagic directory structure,
     * can be found inside the base dir:
     *
     * <pre>
     *  - BaseDir
     *    |- Filter
     *    |- Item
     *    |- Renderer
     *    ^- Rule
     * </pre>
     *
     * @param string $baseDir BaseDir to be added
     *
     * @return Formagic_Autoloader
     */
    public function addBaseDir($baseDir)
    {
        $baseDir = realpath($baseDir);
        if (!in_array($baseDir, $this->baseDirs)) {
            $this->baseDirs[] = $baseDir;
        }

        return $this;
    }

    /**
     * Returns current base dirs
     *
     * @return array
     */
    public function getBaseDirs()
    {
        return $this->baseDirs;
    }

    /**
     * Loads a class located in defined baseDir locations.
     *
     * Skipped if class is already loaded. loadClass() tries to load from any
     * extension directories defined. Returns true if successful, false if not.
     *
     * @param string $class Class name. File name is $class.php
     *
     * @return void
     */
    public function loadClass($class)
    {
        if (class_exists($class, false) || interface_exists($class, false)) {
            return ;
        }

        $chunks = explode('_', $class);
        if (count($chunks) > 1) {
            array_shift($chunks);
            if (!isset($chunks[1])) {
                $chunks[1] = $chunks[0];
            }
        }
        $relative = implode(DIRECTORY_SEPARATOR, $chunks) . '.php';

        // check directories for classes
        foreach ($this->baseDirs as $dir) {
            $file = rtrim($dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $relative;
            if (file_exists($file)) {
                include $file;
                return;
            }
        }
    }
}
