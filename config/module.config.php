<?php
/**
 * module.config.php
 *
 * @copyright Felix Buchheim
 * @author    Felix Buchheim <hanibal4nothing@gmail.com>
 * @version   $Id: $
 */

return array(
    'service_manager' => array(
        'factories'    => array(
            'ZendLog'                        => function () {
                $sFilename = 'log_' . date('d-m-Y') . '.txt';
                $oLogger   = new \Zend\Log\Logger();
                $oWriter   = new \Zend\Log\Writer\Stream('./data/log/' . $sFilename);
                $oLogger->addWriter($oWriter);

                return $oLogger;
            },
            'BaseModule\Logger\ErrorHandler' => function (
                \Zend\ServiceManager\ServiceLocatorInterface $oServiceManager
            ) {
                $oLogger = $oServiceManager->get('ZendLog');
                /* @var Zend\Log\Logger $oLogger */
                $oService = new \BaseModule\Logger\ErrorHandler($oLogger);

                return $oService;
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
