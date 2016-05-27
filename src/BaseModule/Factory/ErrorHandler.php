<?php
namespace BaseModule\Factory;

use BaseModule\Logger\Logger as SysLogger;
use BaseModule\Logger\LoggerCollection;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;

/**
 * Factory to create the ErrorHandler
 *
 * @copyright Felix Buchheim
 * @author    Felix Buchheim <hanibal4nothing@gmail.com>
 */
class ErrorHandler
{
    /**
     * Index key for exceptionLogger
     *
     * @const string
     */
    const EXCEPTION_LOGGER = 'exceptionLogger';

    /**
     * Index key for fatalErrorLogger
     *
     * @const string
     */
    const FATAL_ERROR_LOGGER = 'fatalErrorLogger';

    /**
     * Index key for sysLogger
     *
     * @const string
     */
    const SYS_LOGGER_LOGGER = 'sysLoggerLogger';

    /**
     * @var \BaseModule\Logger\ErrorHandler
     */
    public static $errorHandler;

    /**
     * Build errorHandler on first call
     *
     * @param array $aOptions
     *
     * @return \BaseModule\Logger\ErrorHandler
     */
    public static function factory(array $aOptions)
    {
        if (false === isset(self::$errorHandler)) {

            $oExceptionLogger = (false === empty($aOptions[self::EXCEPTION_LOGGER]['enabled']))
                ? self::createLogger($aOptions[self::EXCEPTION_LOGGER]['path'])
                : null;

            $oErrorLogger = (false === empty($aOptions[self::FATAL_ERROR_LOGGER]['enabled']))
                ? self::createLogger($aOptions[self::FATAL_ERROR_LOGGER]['path'])
                : null;

            $oErrorHandler = new \BaseModule\Logger\ErrorHandler(
                $oExceptionLogger,
                $oErrorLogger,
                self::buildSysLogger($aOptions)
            );

            self::$errorHandler = $oErrorHandler;
        }

        return self::$errorHandler;
    }

    /**
     * Build sysLoggerCollection
     *
     * @param array $aOptions
     *
     * @return LoggerCollection|null
     */
    protected static function buildSysLogger(array $aOptions)
    {
        $oLoggerCollection = null;

        if (false === empty($aOptions[self::SYS_LOGGER_LOGGER]['enabled'])) {
            $oLoggerCollection = new LoggerCollection();

            foreach ($aOptions[self::SYS_LOGGER_LOGGER]['paths'] as $iPriority => $sPath) {
                $oLoggerCollection->add(self::createLogger($sPath, new SysLogger($iPriority)));
            }
        }

        return $oLoggerCollection;
    }

    /**
     * Create single Logger and add writer
     *
     * @param string      $sLogPath
     * @param Logger|null $oLogger
     *
     * @return \Zend\Log\Logger|SysLogger
     */
    protected static function createLogger($sLogPath, Logger $oLogger = null)
    {
        if (false === is_dir($sLogPath)) {
            throw new \RuntimeException('Directory not exist: ' . $sLogPath);
        }

        $oLogger   = (false === isset($oLogger)) ? new Logger() : $oLogger;
        $sFilename = 'log_' . date('d-m-Y') . '.txt';

        return $oLogger->addWriter(new Stream($sLogPath . $sFilename));
    }
}