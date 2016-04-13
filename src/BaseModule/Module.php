<?php
/**
 * Module.php
 *
 * @copyright Felix Buchheim
 * @author    Felix Buchheim <hanibal4nothing@gmail.com>
 * @version   $Id: $
 */

namespace BaseModule;

use BaseModule\Helper\EnvironmentHelper;
use Zend\Config\Config;
use Zend\Di\ServiceLocator;
use Zend\Mvc\MvcEvent;

/**
 * @package   BaseModule
 * @copyright Felix Buchheim
 * @author    Felix Buchheim <hanibal4nothing@gmail.com>
 * @version   $Id: $
 */
class Module
{
    /**
     * @param MvcEvent $oEvent
     */
    public function onBootstrap(MvcEvent $oEvent)
    {
        $oApp            = $oEvent->getTarget();
        $oEventManager   = $oApp->getEventManager();
        $oServiceManager = $oApp->getServiceManager();

        /**
         * Log every none-catched exception
         */
        $oEventManager->attach('dispatch.error', function ($oEvent) use ($oServiceManager) {
            $oException = $oEvent->getResult()->exception;
            if (true === isset ($oException)) {
                $oErrorService = $oServiceManager->get('BaseModule\Logger\ErrorHandler');
                $oErrorService->log($oException);
            }
        });

        $this->fetchEnvironment($oServiceManager);
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return require __DIR__ . '../../config/module.config.php';
    }

    /**
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                    __NAMESPACE__ . 'Test' => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * prepare the EnvironmentHelper
     *
     * @param ServiceLocator $oSm
     *
     * @return $this
     */
    protected function fetchEnvironment(ServiceLocator $oSm)
    {
        $aConfig = $oSm->get('config');
        EnvironmentHelper::fetchEnvironment(new Config($aConfig['baseModuleConfig']['environmentHelper']));

        return $this;
    }
}
