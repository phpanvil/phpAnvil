<?php
/*
 * This file is part of the phpAnvil framework.
 *
 * (c) Nick Slevkoff <nick@phpanvil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace phpAnvil\Component\Application;

use phpAnvil\Component\Object\AbstractProcessObject;
//use phpAnvil\Component\Data\DataConnectionInterface;
use phpAnvil\Component\Locale\Locale;
use phpAnvil\Component\Locale\LocaleInterface;
//use phpAnvil\Component\Formatter\NumberFormatter;
//use phpAnvil\Component\Log\LogRouter;

use phpAnvil\Component\Application\ApplicationInterface;
use phpAnvil\Component\Application\ApplicationException;

//use phpAnvil\Framework\RequestRouter;
//use phpAnvil\Framework\SiteInterface;
use phpAnvil\Component\Application\SourceTypeInterface;
//use phpAnvil\Framework\Exception\NoSiteException;

/**
 * Base abstract class for all %application classes.
 */
abstract class AbstractApplication extends AbstractProcessObject implements ApplicationInterface, SourceTypeInterface
{

    /**
     * Application build number.
     *
     * @var string $build
     */
    protected $build = '0';

    /**
     * Application Copyright
     *
     * @var string $copyright
     */
    protected $copyright = '';

    /**
     * Array of database connections.
     *
     * @var array $databases
     */
    protected $databases = array();

    /**
     * Regional settings object.
     *
     * @var \phpAnvil\Component\Locale\LocaleInterface $locale
     */
    protected $locale;

    /**
     * Request Router.
     *
//     * @var \phpAnvil\Framework\RequestRouter $request
     */
//    protected $request;

    /**
     * The ID for the source that executed the application/process.
     *
     * @var int $sourceID
     */
    protected $sourceID;

    /**
     * Indicates what source type executed the application/process.
     *
     * @var int $sourceTypeID
     */
    protected $sourceTypeID = self::SOURCE_TYPE_USER;

    /**
     * Application name.
     *
     * @var string $name
     */
    protected $name = 'New Application';

    /**
     * Primary Database Index Name
     *
     * @var string $primaryDatabaseName
     */
    protected $primaryDatabaseName;

    /**
     * Application major and minor version number.
     *
     * @var string $version
     */
    protected $version = '1.0';


//    public $configFilename;

//    public $defaultModule;
//    public $defaultAction;
//    public $loginModule;
//    public $loginAction;
//    public $requestedModule;
//    public $requestedAction;


    /**
     * Constructor.
     */
    public function __construct()
    {
//        $this->enableLog();

        $this->locale = new Locale();
//        $this->log = new LogRouter();
//        $this->request = new RequestRouter();
    }


    /**
     * Decrypts a string.
     *
     * @param string $value
     * @param string $key
     *
     * @return string
     */
    public function decrypt($value, $key)
    {
        return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($value), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
    }


    /**
     * Encrypts a string.
     *
     * @param string $value
     * @param string $key
     *
     * @return string
     */
    public function encrypt($value, $key)
    {
        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $value, MCRYPT_MODE_CBC, md5(md5($key))));
    }




    /**
     * Executes the application.
     */
    public function execute()
    {
//        $memoryFormatter = new NumberFormatter(memory_get_usage(true));
//        $this->startLogGroup('open', 'Opening... [M: ' . $memoryFormatter->getBytes() . ']');

        $return = $this->open();

        if (true === $return) {
//            $this->endLogGroup('open');

//            $memoryFormatter->setNumber(memory_get_usage(true));
//            $this->startLogGroup('process', 'Processing... [M: ' . $memoryFormatter->getBytes() . ']');

            $return = $this->process();

            if (true === $return) {
                $return = $this->close();

                if (false === $return) {
                    throw new ApplicationException('Application failed to close.', ApplicationException::OPEN_ERROR);
                }
            } else {
                throw new ApplicationException('Application failed to process.', ApplicationException::PROCESS_ERROR);
            }
//            $this->endLogGroup('process');

//            $memoryFormatter->setNumber(memory_get_usage(true));
//            $this->startLogGroup('close', 'Closing... [M: ' . $memoryFormatter->getBytes() . ']');

        } else {
//            $memoryFormatter->setNumber(memory_get_usage(true));
//            $this->logError('Application failed to open. [M: ' . $memoryFormatter->getBytes() . ']');

//            $this->endLogGroup('open');
            throw new ApplicationException('Application failed to open.', ApplicationException::OPEN_ERROR);
        }

//        $memoryFormatter->setNumber(memory_get_peak_usage(true));
//        $this->logInfo('END OF LINE. [pM: ' . $memoryFormatter->getBytes(). ']');
        return $return;
    }


    /**
     * Generates a random string used as a unique token.
     *
     * @param int  $length
     * @param bool $numbersOnly
     *
     * @return string
     */
    public function generateToken($length = 8, $numbersOnly = false)
    {
        if ($numbersOnly) {
            $charset = "1234567890";
        } else {
            $charset = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        }
        $maxLen = strlen($charset) - 1;

        $token = '';
        for ($i = 0; $i < $length; $i++) {
            $token .= $charset[rand(0, $maxLen)];
        }

        return $token;
    }


    /**
     * Opens the application.
     *
     * @return bool
     */
    public function open()
    {
        //---- Open Primary Database Connection
//        $this->getDatabase()->open();

        //---- Display Application Name and Version
//        $this->logInfo($this->getName() . ' v' . $this->getVersion() . '.' . $this->getBuild());

        //---- Display Copyright
//        $copyright = $this->getCopyright();
//        if (!empty($copyright)) {
//            $this->logInfo($this->getCopyright());
//        }

        //---- Check for Site Object
//        $return = isset($this->site) && ($this->site instanceof SiteInterface);

//        if (false === $return) {
//            throw new NoSiteException('No site object for the application!');
//        } else {
//            $return = $this->site->open();

//            if (false === $return) {
//                $this->logError('Site failed to open.');
//            }
//        }

//        return $return;
        return true;
    }


    /**
     * Processes the application requests.
     */
    public function process()
    {
//        $return = $this->site->process();

//        return $return;
        return true;
    }


    /**
     * Authenticates the user.
     */
    function authenticateUser()
    {
        //        global $phpAnvil;
        //
        //        $phpAnvil->triggerEvent('application.authenticateUser');
        //
        //        return false;
        return true;
    }


    /**
     * Returns the application name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Sets the application name.
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }


    /**
     * @return string
     */
    public function getBuild()
    {
        return $this->build;
    }


    /**
     * @param string $build
     */
    public function setBuild($build)
    {
        $this->build = $build;
    }


    /**
     * @return string
     */
    public function getCopyright()
    {
        return $this->copyright;
    }


    /**
     * @param string $copyright
     */
    public function setCopyright($copyright)
    {
        $this->copyright = $copyright;
    }



    /**
     * @return int
     */
    public function getSourceID()
    {
        return $this->sourceID;
    }


    /**
     * @param int $sourceID
     */
    public function setSourceID($sourceID)
    {
        $this->sourceID = $sourceID;
    }


    /**
     * @return int
     */
    public function getSourceTypeID()
    {
        return $this->sourceTypeID;
    }


    /**
     * @param int $sourceTypeID
     */
    public function setSourceTypeID($sourceTypeID)
    {
        $this->sourceTypeID = $sourceTypeID;
    }


    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }


    /**
     * @param string $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }


}
