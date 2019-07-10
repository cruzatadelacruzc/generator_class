<?php
/**
 * Created by PhpStorm.
 * User: Cesar
 * Date: 9 jul 2019
 * Time: 13:25
 */
require_once 'template.php';

class Processor
{
    private static $filename = 'config.xml';
    private $classes;
    private $folders;
    private $excludes;
    public $output;

    /**
     * Processor constructor.
     * @throws Exception
     */
    public function __construct()
    {
        self::initialized();
    }


    /**
     * Load configuration from file config.xml or config_example.xml  if file config.xml was removed
     * @throws Exception
     */
    private function loadConfiguration()
    {
        try {
            return self::loadFileXML(self::$filename);
        } catch (Exception $exception) {
            $fileconfig = 'config_example.xml';
            $this->output[] = $exception->getMessage() . sprintf(' loading file example configuration "%s" .', $fileconfig);
            return self::loadFileXML($fileconfig);
        }
    }

    /**
     * Load file XML
     * @param $filename
     * @return SimpleXMLElement
     * @throws Exception
     */
    private function loadFileXML($filename)
    {
        if (file_exists($filename)) {
            return simplexml_load_file($filename);
        } else {
            throw new Exception(sprintf('File "%s" does not exist.', $filename));
        }
    }

    /**
     * Set configurations
     * @throws Exception
     */
    function initialized()
    {
        $configurations = self::loadConfiguration();
        // parse source classes
        if (isset($configurations->classes))
            foreach ($configurations->classes->children() as $nodeName => $nodeValue) {
                $this->classes[] = strval($nodeValue);
            }

        // parse source folders
        if (isset($configurations->folders))
            foreach ($configurations->folders->children() as $folder) {
                $excludes = [];
                if (isset($folder->excludes))
                    foreach ($folder->excludes->children() as $exclude) {
                        $excludes[] = strval($exclude);
                    }
                $this->folders[] = ['path' => strval($folder->path), 'excludes' => $excludes];
            }
    }

    /**
     * Generate all PHP classes and Folders
     * @param $bundle
     * @param $project
     * @return bool
     * @throws Exception
     */
    public function generateProject($bundle, $project)
    {
        $bundle = preg_replace('/\s+/', '', $bundle); // remove all whitespace
        $project = preg_replace('/\s+/', '', $project); // remove all whitespace

        foreach ($this->folders as $folder) {
            $files = self::dirToArray($folder['path']);
            $classes = array_map(function ($item) {
                return rtrim($item, '.php');
            }, $files);
            if (!empty($folder['excludes']))
                $classes = array_diff($classes, $folder['excludes']);
            self::generateClass($classes, $bundle, $project);
        }

        // Generate only classes
        if (!empty($this->classes)) {
            self::generateClass($this->classes, $bundle, $project);
        }

        $this->output[] = '<strong>Everything is OK! Now get to work :).</strong>';
        return true;
    }


    /**
     * Generate class
     * @param $classes
     * @param $bundle
     * @param $project
     * @throws Exception
     */
    private function generateClass($classes, $bundle, $project)
    {
        foreach ($classes as $class) {
            self::createClass($bundle, $class, $project);
        }
    }


    /**
     * sourceClass -> class path
     * @param $bundle
     * @param $sourceClass
     * @param $project
     * @throws Exception
     */
    function createClass($bundle, $sourceClass, $project)
    {
        $classname = explode("\\", $sourceClass);
        $parent = end($classname);
        $parent_use = 'use ' . $sourceClass . ';';
        $filename = $bundle . end($classname);
        $output_dir = dirname(__FILE__, 2) . DIRECTORY_SEPARATOR . 'project' . DIRECTORY_SEPARATOR;
        $project_path = $output_dir . $project . DIRECTORY_SEPARATOR;
        $namespace = $bundle . '\\' . str_replace('/', '\\', $project);
        $parameters = [
            'name' => $filename,
            'namespace' => $namespace,
            'parent' => $parent,
            'parent_use' => $parent_use
        ];
        $target = $project_path . $filename . '.php';
        self::renderFile('template.php', $parameters, $target);
    }

    /**
     * Create Folder and PHP Class
     * @param $template
     * @param array $parameters
     * @param $target
     * @return bool|int
     * @throws Exception
     */
    public function renderFile($template, $parameters, $target)
    {
        self::mkdir(dirname($target));
        return self::dump($target, $this->render($template, $parameters));

    }

    /**
     * @param $view
     * @param array $parameters
     * @return false|string
     * @throws Exception
     */
    function render($view, array $parameters = array())
    {
        if (!empty($parameters)) {
            if (is_string($view)) {
                ob_start();
                require($view);
                return ob_get_clean();
            } else {
                throw new Exception(sprintf('The View content must be a string, "%s" given.', \gettype($view)));
            }
        } else
            return null;

    }

    /**
     * @param $dir
     * @param int $mode
     * @param bool $recursive
     */
    private function mkdir($dir, $mode = 0777, $recursive = true)
    {
        if (!is_dir($dir)) {
            mkdir($dir, $mode, $recursive);
            $this->output[] = sprintf('  <span style="color: green">created</span> %s', self::relativizePath($dir));
        }
    }

    /**
     * @param $filename
     * @param $content
     * @return bool|int
     */
    private function dump($filename, $content)
    {
        if (file_exists($filename)) {
            $this->output[] = sprintf('  <span style="color: #856404">update</span> %s', self::relativizePath($filename));
        } else {
            $this->output[] = sprintf('  <span style="color: #155724">created</span> %s', self::relativizePath($filename));
        }

        return file_put_contents($filename, $content);
    }

    /**
     * @param $absolutePath
     * @return mixed|string
     */
    private function relativizePath($absolutePath)
    {
        $relativePath = str_replace(getcwd(), '.', $absolutePath);

        return is_dir($absolutePath) ? rtrim($relativePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR : $relativePath;
    }

    /**
     * Create an array of my directory structure recursively
     * @param $dir
     * @return array
     */
    function dirToArray($dir)
    {
        $result = array();
        $cdir = scandir($dir);
        foreach ($cdir as $key => $value) {
            if (!in_array($value, array(".", ".."))) {
                if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
                    $result[$value] = self::dirToArray($dir . DIRECTORY_SEPARATOR . $value);
                } else {
                    $result[] = $value;
                }
            }
        }
        return $result;
    }


}