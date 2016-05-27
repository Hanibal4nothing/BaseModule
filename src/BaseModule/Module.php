<?php

namespace BaseModule;

use BaseModule\Helper\EnvironmentHelper;
use BaseModule\Logger\ErrorHandler;
use BaseModule\Factory\ErrorHandler as ErrorHandlerFactory;
use Zend\Config\Config;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceManager;

/**
 * Module.php
 *
 * @copyright Felix Buchheim
 * @author    Felix Buchheim <hanibal4nothing@gmail.com>
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
        $oErrorService   = $oServiceManager->get('BaseModule\Logger\ErrorHandler');
        /* @var ErrorHandler $oErrorService */
        $aConfig = $oServiceManager->get('config');

        if (true === $aConfig['baseModule']['errorHandler'][ErrorHandlerFactory::SYS_LOGGER_LOGGER]['enabled']) {
            $oErrorService->registerAsErrorHandler();
        }
        if (true === $aConfig['baseModule']['errorHandler'][ErrorHandlerFactory::FATAL_ERROR_LOGGER]['enabled']) {
            $oErrorService->registerFatalErrorHandler();
        }
        if (true === $aConfig['baseModule']['errorHandler'][ErrorHandlerFactory::EXCEPTION_LOGGER]['enabled']) {
            /**
             * Log every none-catched exception
             */
            $oEventManager->attach('dispatch.error', function ($oEvent) use ($oErrorService) {
                $oException = $oEvent->getResult()->exception;
                if (true === isset ($oException)) {
                    $oErrorService->log($oException);
                }
            });

        }

        $this->fetchEnvironment($oServiceManager);
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return require __DIR__ . '/../../config/module.config.php';
    }

    /**
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__          => __DIR__ . '/src/' . __NAMESPACE__,
                    __NAMESPACE__ . 'Test' => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * prepare the EnvironmentHelper
     *
     * @param ServiceManager $oSm
     *
     * @return $this
     */
    protected function fetchEnvironment(ServiceManager $oSm)
    {
        $aConfig = $oSm->get('config');
        EnvironmentHelper::fetchEnvironment(new Config($aConfig['baseModule']['environmentHelper']));

        return $this;
    }
}
