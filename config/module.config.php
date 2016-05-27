<?php
/**
 * module.config.php
 *
 * @copyright Felix Buchheim
 * @author    Felix Buchheim <hanibal4nothing@gmail.com>
 */

return array(
    'service_manager' => array(
        'factories'    => array(
            'BaseModule\Logger\ErrorHandler' => function (
                \Zend\ServiceManager\ServiceLocatorInterface $oServiceManager
            ) {
                $aConfig  = $oServiceManager->get('config');
                $aOptions = $aConfig['baseModule']['errorHandler'];

                return \BaseModule\Factory\ErrorHandler::factory($aOptions);
            },
        ),
        'invokables'   => array(
            'BaseModule\Helper\AppConfig' => 'BaseModule\Helper\AppConfig',
        ),
        'initializers' => array(
            function ($oInstance, $oServiceManager) {
                if ($oInstance instanceof \BaseModule\Helper\AppConfig) {
                    $oInstance->fetchConfig($oServiceManager);
                }
            },
        ),
    ),
    'view_manager'    => array(
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);
