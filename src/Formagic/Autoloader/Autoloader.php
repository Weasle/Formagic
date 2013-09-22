<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Florian
 * Date: 12.09.13
 * Time: 20:32
 * To change this template use File | Settings | File Templates.
 */

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
    public function __construct(array $baseDirs)
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
     * @return Formagic_Autoloaders
     */
    public static function register($baseDirs = array(), $prepend = false)
    {
        self::$instance = new Formagic_Autoloader($baseDirs);
        spl_autoload_register(array(self::$instance, 'loadClass'), true, $prepend);

        return self::$instance;
    }

    /**
     * Adds a directory to base dir stack (globally for all Formagic instances).
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
                include($file);
                return;
            }
        }
    }
}
