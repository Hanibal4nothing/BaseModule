<?php

return array(
    'baseModuleConfig' => array(
        'environmentHelper' => array(
            /**
             * ServerAddress for development
             */
            'devServerAddress'        => '127.0.0.1',

            /**
             * ServerAddress for production
             */
            'productionServerAddress' => '127.0.0.1',

            /**
             * are the serverAddresses doesn't match, the environment will be the given value
             */
            'inDoubt'       => \BaseModule\Helper\EnvironmentHelper::PRODUCTION,

            /**
             * Set the environment hard to this option
             *
             * available options :
             * - \BaseModule\Helper\EnvironmentHelper::DEVELOPMENT
             * - \BaseModule\Helper\EnvironmentHelper::PRODUCTION
             * - \BaseModule\Helper\EnvironmentHelper::DISABLE_HARD_ENVIRONMENT
             */
            'hardEnvironment'         => \BaseModule\Helper\EnvironmentHelper::DISABLE_HARD_ENVIRONMENT,

            /**
             * activate the feature to switch the environment by url
             *
             * to use this change the uro to www.yourApplication/someResource?dev=0
             *
             * dev=0 to activate the production
             * dev=1 to activate the development
             */
            'enableSwitchViaUrl' => true,

            /**
             * set this options on module bootstrap for the estimated environment
             */
            'environments' => array(
                \BaseModule\Helper\EnvironmentHelper::PRODUCTION => array(
                    'displayErrors' => 0,
                    'errorReporting' => E_CORE_ERROR
                ),
                \BaseModule\Helper\EnvironmentHelper::DEVELOPMENT => array(
                    'displayErrors' => 1,
                    'errorReporting' => E_ALL
                ),
            )
        )
    )
);