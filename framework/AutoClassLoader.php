<?php
namespace phpAnvil\Framework;

/*
 * phpAnvil Framework
 *
 * Copyright (c) 2009-2011 Nick Slevkoff
 *
 * LICENSE
 *
 * This source file is subject to the MIT License that is bundled with this
 * package in the file LICENSE.md.  It is also available online at the following:
 * - http://www.phpanvil.com/LICENSE.md
 * - http://www.opensource.org/licenses/mit-license.php
 */


require_once dirname(__FILE__) . '/../component/ObjectAbstract.php';

//use \phpAnvil\Component\ObjectAbstract;

/**
 * AutoClassLoader implementation that implements the technical interoperability
 * standards for PHP 5.3 namespaces and class names.
 *
 * http://groups.google.com/group/php-standards/web/final-proposal
 *
 * @copyright   Copyright (c) 2009-2011 Nick Slevkoff
 * @license     MIT License
 *      Full copyright and license information is available in the LICENSE.md
 *      file that was distributed with this source file or can be found online
 *      at http://www.phpanvil.com/LICENSE.md
 */
class AutoClassLoader extends ObjectAbstract
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
    private $_namespaces = array();


    public function __construct()
    {
        //        $this->enableLog();
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
        $this->_namespaces = $namespaces;
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
        $this->_namespaces[$namespace][] = $path;
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
        //        if ($file = $this->_findClass($class)) {
        //            require $file;
        //        }
        $this->_findClass($class);
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
    private function _findClass($class)
    {
        $isFound = false;
        $isDone = false;


        //---- Convert _ to Namespace Separators -------------------------------
        $class = str_replace('_', $this->namespaceSeparator, $class);


        //---- Remove Leading Separator ----------------------------------------
        if ($class[0] == $this->namespaceSeparator) {
            $class = substr($class, 1);
        }


        /*
               if (null === $this->_namespace || $this->_namespace.$this->_namespaceSeparator === substr($className, 0, strlen($this->_namespace.$this->_namespaceSeparator))) {
                   $fileName = '';
                   $namespace = '';
                   if (false !== ($lastNsPos = strripos($className, $this->_namespaceSeparator))) {
                       $namespace = substr($className, 0, $lastNsPos);
                       $className = substr($className, $lastNsPos + 1);
                       $fileName = str_replace($this->_namespaceSeparator, DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
                   }
                   $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . $this->_fileExtension;

                   require ($this->_includePath !== null ? $this->_includePath . DIRECTORY_SEPARATOR : '') . $fileName;
               }

        */

        //        echo '$class = ' . $class . "<br />\n";
        $this->logDebug('$class', $class);

        $namespaceLength = strrpos($class, $this->namespaceSeparator);
        $classFilePath = '';
        $classLength = strlen($class);
        $className = substr($class, $namespaceLength + 1);

        //        echo '$className = ' . $className . "<br />\n";
        $this->logDebug('$className', $className);

        while (!$isFound && !$isDone)
        {
            //            echo '$namespaceLength = ' . $namespaceLength . "<br />\n";
            $this->logDebug('$namespaceLength', $namespaceLength);

            //---- Extract Namespace -------------------------------------------
            $namespace = substr($class, 0, $namespaceLength);

            //            echo '$namespace = ' . $namespace . "<br />\n";
            $this->logDebug('$namespace', $namespace);

            //---- Check for Exact Namespace Match -----------------------------
            $isFound = array_key_exists($namespace, $this->_namespaces);

            if ($isFound) {
                //                echo '** Exact Namespace Match!<br />' . "\n";

                if (!strpos($namespace, $this->namespaceSeparator)) {
                    $subNamespace = substr($class, strlen($namespace) + 1, $classLength - strlen($namespace) - strlen($className) - 1);

                } else {
                    $subNamespace = substr($class, strlen($namespace) + 1, $classLength - strlen($namespace) - strlen($className) - 2);
                }
                $subNamespace = str_replace($this->namespaceSeparator, DIRECTORY_SEPARATOR, $subNamespace);

                $this->logDebug('$subNamespace', $subNamespace);

                //                echo '$subNamespace = ' . $subNamespace . "<br />\n";


                //---- Reset Found Until Actual Path Found
                $isFound = false;

                //---- Check Directory Paths -----------------------------------
                $maxPaths = count($this->_namespaces[$namespace]);

                if ($maxPaths == 1) {
                    $classFilePath = str_replace($this->namespaceSeparator, DIRECTORY_SEPARATOR, $this->_namespaces[$namespace][0]) . DIRECTORY_SEPARATOR;
                    $classFilePath .= $subNamespace . DIRECTORY_SEPARATOR;
                    $classFilePath .= $className . $this->fileExtension;

                    $isFound = require_once($classFilePath);
                } else {
                    for ($i = 0; $i < $maxPaths; $i++)
                    {
                        $classFilePath = str_replace($this->namespaceSeparator, DIRECTORY_SEPARATOR, $this->_namespaces[$namespace][$i]) . DIRECTORY_SEPARATOR;
                        $classFilePath .= $subNamespace . DIRECTORY_SEPARATOR;
                        $classFilePath .= $className . $this->fileExtension;

                        //                        echo '$i = ' . $i . "<br />\n";
                        //                        echo '$classFilePath = ' . $classFilePath . "<br />\n";
                        $this->logDebug($i . ') $classFilePath', $classFilePath);

                        $isFound = require_once($classFilePath);
                        if ($isFound) {
                            break;
                        }
                    }

                }
            }

            if ($isFound) {
                //                echo '** Class File Found in Path (' . $classFilePath . ')<br />' . "\n";
                $this->logInfo('Class Found', $classFilePath);
            } else {
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
