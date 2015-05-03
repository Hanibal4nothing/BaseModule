<?php
/**
 * ErrorHandler.php
 *
 * @copyright Felix Buchheim
 * @version   $Id: $
 */

namespace BaseModule\Logger;

use Zend\Log\Logger;

/**
 * Simple Logger to log Exceptions
 *
 * @package   BaseModule\Logger
 * @copyright Felix Buchheim
 * @author    Felix Buchheim <hanibal4nothing@gmail.com>
 * @version   $Id: $
 */
class ErrorHandler
{

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @param Logger $oLogger
     */
    public function __construct(Logger $oLogger)
    {
        $this->logger = $oLogger;
    }

    /**
     * @param \Exception $e
     */
    public function log(\Exception $e)
    {
        $sTrace    = $e->getTraceAsString();
        $iCounter  = 1;
        $aMessages = array();
        do {
            $aMessages[] = ($iCounter++) . ": \n" . $e->getMessage();
        } while ($e = $e->getPrevious());

        $sLog = 'Exception ' . implode(PHP_EOL, $aMessages);
        $sLog .= " \nTrace: \n" . $sTrace;

        $this->logger->err($sLog);
    }
}