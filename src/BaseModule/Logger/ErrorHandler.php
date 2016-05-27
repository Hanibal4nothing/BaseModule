<?php

namespace BaseModule\Logger;

use Zend\Log\Logger;

/**
 * Simple Logger to log Exceptions
 *
 * @copyright Felix Buchheim
 * @author    Felix Buchheim <hanibal4nothing@gmail.com>
 */
class ErrorHandler
{
    /**
     * Logger for exceptions
     *
     * @var Logger
     */
    protected $exceptionLogger;

    /**
     * Logger for fatalErrors
     *
     * @var Logger
     */
    protected $fatalErrorLogger;

    /**
     * Multiple logger for sysLog
     *
     * @var LoggerCollection
     */
    protected $sysLogger;

    /**
     * ErrorHandler constructor.
     *
     * @param Logger|null           $oExceptionLogger
     * @param Logger|null           $oFatalErrorLogger
     * @param LoggerCollection|null $oSysLogger
     */
    public function __construct(
        Logger $oExceptionLogger = null,
        Logger $oFatalErrorLogger = null,
        LoggerCollection $oSysLogger = null
    ) {
        $this->exceptionLogger  = $oExceptionLogger;
        $this->fatalErrorLogger = $oFatalErrorLogger;
        $this->sysLogger        = $oSysLogger;
    }

    /**
     * Log a single exception
     *
     * @param \Exception $e
     *
     * @return $this
     */
    public function log(\Exception $e)
    {
        if (false === isset($this->exceptionLogger)) {
            throw new \RuntimeException('ExceptionLogger is not set');
        }

        $sTrace    = $e->getTraceAsString();
        $iCounter  = 1;
        $aMessages = array();
        do {
            $aMessages[] = ($iCounter++) . ": \n" . $e->getMessage();
        } while ($e = $e->getPrevious());

        $sLog = 'Exception ' . implode(PHP_EOL, $aMessages);
        $sLog .= " \nTrace: \n" . $sTrace;

        $this->exceptionLogger->err($sLog);

        return $this;
    }

    /**
     * Register different logger as errorHandler
     *
     * @return $this
     */
    public function registerAsErrorHandler()
    {
        if (false === isset($this->sysLogger)) {
            throw new \RuntimeException('Syslogger are not set');
        }

        foreach ($this->sysLogger as $iPriority => $oLogger) {
            set_error_handler(function ($iCode, $sMessage, $sFile, $iLine) use ($oLogger) {
                $sType = Logger::$errorPriorityMap[$iCode];

                /* @var \BaseModule\Logger\Logger $oLogger */
                $oLogger->log($sType, $sMessage . PHP_EOL, [
                    'file' => $sFile,
                    'line' => $iLine,
                ]);
            }, $iPriority);
        }

        return $this;
    }

    /**
     * Register the fatalErrorLogger zu log fatalErrors
     *
     * @return $this
     */
    public function registerFatalErrorHandler()
    {
        if (false === isset($this->fatalErrorLogger)) {
            throw new \RuntimeException('FatalErrorLogger are not set');
        }

        Logger::registerFatalErrorShutdownFunction($this->fatalErrorLogger);

        return $this;
    }
}