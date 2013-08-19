<?php

namespace phpAnvil\Component\Loader;

/**
 * Primary auto loader.
 */
class AutoLoader
{
    //---- Option Variables ----------------------------------------------------

    /**
     * String containing the default file extension used for all namespace files.
     */
    public $fileExtension = '.php';

    /**
     * String containing the namespace separator used in all namespace paths.
     */
    public $namespaceSeparator = '\\';


    //---- Namespace Array Variables -------------------------------------------

    /**
     * Array containing all registered namespace paths.
     */
    private $namespaces = array();


    /**
     * Constructor.
     */
    public function __construct()
    {
    }


    /**
     * Registers a namespace path.
     *
     * @param string $namespace
     *      Base namespace referenced for the path.
     * @param string $path
     *      The path location of source files for the namespace.
     *
     * @return NULL
     */
    public function addNamespace($namespace, $path)
    {
        $this->namespaces[$namespace][] = $path;
    }


    /**
     * Loads the given class or interface.
     *
     * @param string $class
     *      The name of the class to load.
     *
     * @return NULL
     */
    public function loadClass($class)
    {
        $this->findClass($class);
    }


    /**
     * Registers this instance as an autoloader.
     *
     * @param boolean $prepend
     *      If TRUE, this class loader will be prepended in the autoloader list.
     *
     * @return boolean
     *
     * @par
     *      Returns TRUE on success, or FALSE on failure.
     */
    public function open($prepend = false)
    {
        spl_autoload_register(array($this, 'loadClass'), true, $prepend);

//        echo '<pre>';
//        print_r($this->namespaces);
//        echo '</pre>';
    }


    /**
     * Registers an array of namespaces
     *
     * @param array $namespaces
     *      An array of namespaces (namespace => path)
     *
     * @return NULL
     */
    public function registerNamespaces(array $namespaces)
    {
        $this->namespaces = $namespaces;
    }


    /**
     * Finds the path to the file where the class is located.
     *
     * @param string $class
     *      The name of the class to load.
     *
     * @return boolean
     *
     * @par
     *      Returns TRUE if the class was found and loaded, or FALSE on failure.
     */
    private function findClass($class)
    {
        $isFound = false;
        $isDone = false;


        //---- Convert _ to Namespace Separators -------------------------------
        $class = str_replace('_', $this->namespaceSeparator, $class);


        //---- Remove Leading Separator ----------------------------------------
        if ($class[0] == $this->namespaceSeparator) {
            $class = substr($class, 1);
        }


        $namespaceLength = strrpos($class, $this->namespaceSeparator);
        $classLength = strlen($class);
        $className = substr($class, $namespaceLength + 1);

        while (!$isFound && !$isDone) {

            //---- Extract Namespace -------------------------------------------
            $namespace = substr($class, 0, $namespaceLength);

            //---- Check for Exact Namespace Match -----------------------------
            $isFound = array_key_exists($namespace, $this->namespaces);

            if ($isFound) {

                if (!strpos($namespace, $this->namespaceSeparator)) {
                    $subNamespace = substr($class, strlen($namespace) + 1, $classLength - strlen($namespace) - strlen($className) - 1);

                } else {
                    $subNamespace = substr($class, strlen($namespace) + 1, $classLength - strlen($namespace) - strlen($className) - 2);
                }
                $subNamespace = str_replace($this->namespaceSeparator, DIRECTORY_SEPARATOR, $subNamespace);

                //---- Reset Found Until Actual Path Found
                $isFound = false;

                //---- Check Directory Paths -----------------------------------
                $maxPaths = count($this->namespaces[$namespace]);

                if ($maxPaths == 1) {

                    $classFilePath = str_replace($this->namespaceSeparator, DIRECTORY_SEPARATOR, $this->namespaces[$namespace][0]) . DIRECTORY_SEPARATOR;

                    if (!empty($subNamespace)) {
                        $classFilePath .= $subNamespace;
                    }

                    $classFilePath .= $className . $this->fileExtension;

                    $isFound = require_once($classFilePath);
                } else {
                    for ($i = 0; $i < $maxPaths; $i++) {
                        $classFilePath = str_replace($this->namespaceSeparator, DIRECTORY_SEPARATOR, $this->namespaces[$namespace][$i]) . DIRECTORY_SEPARATOR;
                        if (!empty($subNamespace)) {
                            $classFilePath .= $subNamespace . DIRECTORY_SEPARATOR;
                        }
                        $classFilePath .= $className . $this->fileExtension;

                        $isFound = require_once($classFilePath);
                        if ($isFound) {
                            break;
                        }
                    }

                }
            }

            if (!$isFound) {
                $namespaceLength = strrpos($class, $this->namespaceSeparator, -($classLength - $namespaceLength + 1));
                $isDone = !$namespaceLength;
            }
        }

        return $isFound;
    }


    /**
     * Unregisters this class loader from the SPL autoloader stack and closes
     * the object.
     *
     * @return NULL
     */
    public function close()
    {
        spl_autoload_unregister(array($this, 'loadClass'));
    }
}
