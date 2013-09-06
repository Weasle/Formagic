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
 * @copyright   Copyright (c) 2012 Florian Sonnenburg
 * @license     http://www.formagic-php.net/license-agreement/   New BSD License
 */
namespace Formagic\Generator\Writer;

use Formagic\Generator\Properties\PropertiesInterface;
use Formagic\Generator\InjectionContainer\Container;

/**
 * Formagic translator class
 *
 * @category    Formagic
 * @package     Generator
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2012 Florian Sonnenburg
 * @version     $Id: $
 */
class FileWriter implements WriterInterface
{
    /**
     * @var string
     */
    const CACHE_DIR = 'FormagicFormCache';

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var string
     */
    protected $outputDir;

    /**
     * @var string
     */
    protected $formagicDir;

    /**
     * Constructor
     *
     * @param Container $container
     * @param string $outputDir
     * @param string $formagicDir
     */
    public function __construct(Container $container, $outputDir, $formagicDir = '')
    {
        $this->container = $container;
        $this->outputDir = $outputDir;
        if (!empty($formagicDir)) {
            $this->formagicDir = rtrim($formagicDir, '/') . '/';
        }
    }

    /**
     * {@inheritDoc}
     */
    public function write(PropertiesInterface $properties)
    {
        $lines[] = '<?php';

        $this->createOutputDir();

        /* @var $formFormatter \Formagic\Generator\CodeFormatter\FormFormatter */
        $formFormatter = $this->container['formFormatter'];

        $formCode = $formFormatter->format($properties);

        $includeList = $this->getIncludeList();
        foreach ($includeList as $include) {
            $lines[] = "require_once '{$this->formagicDir}{$include}';";
        }

        $formagicClassesList = $this->getFormagicClassesList();
        foreach ($formagicClassesList as $formagicClass) {
            $lines[] = "Formagic::loadClass('{$formagicClass}');";
        }

        $lines[] = '';
        $lines[] = $formCode;

        $code = implode(PHP_EOL, $lines);
        $filename = 'Form_' . $properties->getFormId() . '.php';
        $outputPath = $this->outputDir . '/' . self::CACHE_DIR . '/' . $filename;

        file_put_contents($outputPath, $code);

        return $outputPath;
    }

    /**
     * @return array
     */
    protected function getIncludeList()
    {
        /* @var $includesRegistry ResourceRegistry */
        $includesRegistry = $this->container['resourceRegistry'];
        $includes = $includesRegistry->getResources();
        return $includes;
    }

    /**
     * @return array
     */
    protected function getFormagicClassesList()
    {
        /* @var $includesRegistry ResourceRegistry */
        $includesRegistry = $this->container['resourceRegistry'];
        $includes = $includesRegistry->getFormagicClasses();
        return $includes;
    }
    /**
     * @throws NotWritableException
     */
    protected function createOutputDir()
    {
        $dir = $this->outputDir . '/' . self::CACHE_DIR;
        if (!file_exists($dir)) {
            if (is_writable($this->outputDir)) {
                mkdir($dir);
            } else {
                throw new NotWritableException('Directory ' . $this->outputDir . ' not writable');
            }
        }
    }
}
