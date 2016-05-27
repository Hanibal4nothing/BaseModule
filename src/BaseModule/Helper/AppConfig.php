<?php

namespace BaseModule\Helper;

use Zend\Config\Config;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Simple Config
 *
 * to difference the zf2 config with routes etc, and the application-config for the usage
 *
 * @copyright Felix Buchheim
 * @author    Felix Buchheim <hanibal4nothing@gmail.com>
 */
class AppConfig
{
    /**
     * key to fetch the config
     *
     * @var string
     */
    const CONFIG_KEY = 'applicationConfig';

    /**
     * @var Config
     */
    protected $config;

    /**
     * @param ServiceLocatorInterface $oServiceManager
     *
     * @return $this
     */
    public function fetchConfig(ServiceLocatorInterface $oServiceManager)
    {
        $aConfig      = $oServiceManager->get('config');
        $this->config = new Config($aConfig[self::CONFIG_KEY], false);

        return $this;
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param $sKey
     *
     * @return mixed|null
     */
    public function get($sKey)
    {
        return (true === $this->config->offsetExists($sKey) ? $this->config->get($sKey) : null);
    }

} 