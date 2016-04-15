<?php
/**
 * EnvironmentHelper.php
 *
 * @copyright Felix Buchheim
 * @version   $Id: $
 */

namespace BaseModule\Helper;

use Zend\Config\Config;

/**
 * To different the environment
 *
 * @package   BaseModule\Helper
 * @copyright Felix Buchheim
 * @author    Felix Buchheim <hanibal4nothing@gmail.com>
 * @version   $Id: $
 */
class EnvironmentHelper
{
    /**
     * @var: string
     */
    const PRODUCTION = 'production';

    /**
     * @var: string
     */
    const DEVELOPMENT = 'development';

    /**
     * @var string
     */
    const DISABLE_HARD_ENVIRONMENT = 'disableHardEnvironment';

    /**
     * @var string
     */
    protected static $environment;

    /**
     * @return string
     */
    static public function getEnvironment()
    {
        return self::$environment;
    }

    /**
     * @return bool
     */
    static public function isProduction()
    {
        return (self::PRODUCTION === self::$environment);
    }

    /**
     * @return bool
     */
    static public function isDevelopment()
    {
        return (self::DEVELOPMENT === self::$environment);
    }

    /**
     * Estimate the environment from Config, Url or ServerAddress
     *
     * @param Config $oConfig
     *
     * @return bool
     */
    static public function fetchEnvironment(Config $oConfig)
    {
        self::$environment = null;
        $bEnvironmentFetched = false;

        if (true === $oConfig->offsetExists('enableSwitchViaUrl') and true === $oConfig->get('enableSwitchViaUrl')) {
            $bEnvironmentFetched = self::fetchEnvironmentByUrl();
        }
        if (false === $bEnvironmentFetched) {
            if (true === $oConfig->offsetExists('hardEnvironment') and $oConfig->get('hardEnvironment') !== self::DISABLE_HARD_ENVIRONMENT){
                self::$environment = $oConfig->get('hardEnvironment');
                $bEnvironmentFetched = true;
            } else {
                $bEnvironmentFetched = self::fetchEnvironmentByServer($oConfig);
            }
        }

        if(false === $bEnvironmentFetched) {
            self::$environment = $oConfig->get('inDoubt');
        }

        if (self::$environment !== self::PRODUCTION and self::$environment !== self::DEVELOPMENT){
            throw new \RuntimeException('Coud not estimate environment');
        }

        return $bEnvironmentFetched;
    }

    /**
     * try to fetch the environment by url
     *
     * @return bool
     */
    static protected function fetchEnvironmentByUrl()
    {
        $sQueryString = (false === empty($_SERVER['QUERY_STRING'])) ? $_SERVER['QUERY_STRING'] : '';
        $aMatches = array();
        $bEnvironment = false;

        if (1 === preg_match('/dev=([^&]*)/', $sQueryString, $aMatches)) {
            switch($aMatches[1]){
                case '1':
                    self::$environment = self::DEVELOPMENT;
                    $bEnvironment = true;
                    break;
                case '0':
                    self::$environment = self::PRODUCTION;
                    $bEnvironment = true;
                    break;
                default:
            }
        }

        return $bEnvironment;
    }

    /**
     * try to fetch the environment by the serverAddress
     *
     * @param Config $oConfig
     *
     * @return bool
     */
    static protected function fetchEnvironmentByServer(Config $oConfig)
    {
        $sServerAddress = (false === empty($_SERVER['SERVER_ADDR'])) ? $_SERVER['SERVER_ADDR'] : '';
        $bEnvironmentFetched = false;

        if (true === $oConfig->offsetExists('devServerAddress') and $oConfig->get('devServerAddress') === $sServerAddress) {
            self::$environment = self::DEVELOPMENT;
            $bEnvironmentFetched = true;
        }

        if (true === $oConfig->offsetExists('productionServerAddress') and $oConfig->get('productionServerAddress') === $sServerAddress) {
            self::$environment = self::PRODUCTION;
            $bEnvironmentFetched = true;
        }

        return $bEnvironmentFetched;
    }
} 