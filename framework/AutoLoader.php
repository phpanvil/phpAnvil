<?php
namespace phpAnvil\Framework;

/**
 * This file is part of the phpAnvil framework.
 *
 * (c) Nick Slevkoff <nick@phpanvil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


require_once(PHPANVIL2_PATH . 'Component/ObjectAbstract.php');

use \phpAnvil\Component\ObjectAbstract;


/**
 * AutoClassLoader implementation that implements the technical interoperability
 * standards for PHP 5.3 namespaces and class names.
 *
 * http://groups.google.com/group/php-standards/web/final-proposal
 */
class AutoLoader extends ObjectAbstract
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


    /**
     * Constructor.
     */
    public function __construct()
    {
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
        $isDone  = false;


        //---- Convert _ to Namespace Separators -------------------------------
        $class = str_replace('_', $this->namespaceSeparator, $class);


        //---- Remove Leading Separator ----------------------------------------
        if ($class[0] == $this->namespaceSeparator) {
            $class = substr($class, 1);
        }

        $this->_logDebug('$class', $class);

        $namespaceLength = strrpos($class, $this->namespaceSeparator);
        $classFilePath   = '';
        $classLength     = strlen($class);
        $className       = substr($class, $namespaceLength + 1);

        $this->_logDebug('$className', $className);

        while (!$isFound && !$isDone) {
            $this->_logDebug('$namespaceLength', $namespaceLength);

            //---- Extract Namespace -------------------------------------------
            $namespace = substr($class, 0, $namespaceLength);

            $this->_logDebug('$namespace', $namespace);

            //---- Check for Exact Namespace Match -----------------------------
            $isFound = array_key_exists($namespace, $this->_namespaces);

            if ($isFound) {

                if (!strpos($namespace, $this->namespaceSeparator)) {
                    $subNamespace = substr($class, strlen($namespace) + 1, $classLength - strlen($namespace) - strlen($className) - 1);

                } else {
                    $subNamespace = substr($class, strlen($namespace) + 1, $classLength - strlen($namespace) - strlen($className) - 2);
                }
                $subNamespace = str_replace($this->namespaceSeparator, DIRECTORY_SEPARATOR, $subNamespace);

                $this->_logDebug('$subNamespace', $subNamespace);

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
                    for ($i = 0; $i < $maxPaths; $i++) {
                        $classFilePath = str_replace($this->namespaceSeparator, DIRECTORY_SEPARATOR, $this->_namespaces[$namespace][$i]) . DIRECTORY_SEPARATOR;
                        $classFilePath .= $subNamespace . DIRECTORY_SEPARATOR;
                        $classFilePath .= $className . $this->fileExtension;

                        $this->_logDebug($i . ') $classFilePath', $classFilePath);

                        $isFound = require_once($classFilePath);
                        if ($isFound) {
                            break;
                        }
                    }

                }
            }

            if ($isFound) {
                $this->_logInfo('Class Found', $classFilePath);
            } else {
                $namespaceLength = strrpos($class, $this->namespaceSeparator, -($classLength - $namespaceLength + 1));
                $isDone          = !$namespaceLength;
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

/*
function anvilDefine($name, $value) {
    defined($name) or define($name, $value);
}

function anvilRequire($filepath)
{
    if (strstr($filepath, '/') == false) {
        $filename = basename($filepath);
        $filenameArray = explode('.', basename($filename, '.php'));

        switch (strtolower(max($filenameArray))) {
            case 'model':
                if (defined('APP_MODEL_PATH') && file_exists(APP_MODEL_PATH . $filename)) {
                    $filepath = APP_MODEL_PATH . $filename;
                } else {
                    if (defined('PHPANVIL2_MODEL_PATH') && file_exists(PHPANVIL2_MODEL_PATH . $filename)) {
                        $filepath = PHPANVIL2_MODEL_PATH . $filename;
                    }
                }
                break;

        }
    }

    require_once($filepath);
}
*/

?>
