<?php
/**
 * @copyright Copyright (c) 2015 Hanibal
 * @package   BaseModuleTest\Dummy
 * @author    Felix Buchheim -> hanibal4nothing@gmail.com
 * @author    Hanibal
 * @version   $Id: $
 */


namespace BaseModuleTest\Dummy;

use BaseModule\Controller\AbstractBaseController;
use Zend\Stdlib\Parameters;

/**
 * Dummy to test AbstractBaseController (to public protected functions)
 *
 * @copyright Copyright (c) 2015 Hanibal
 * @package   BaseModuleTest\Dummy
 * @author    Felix Buchheim -> hanibal4nothing@gmail.com
 * @author    Hanibal
 * @version   $Id: $
 */
class BaseControllerDummy extends AbstractBaseController
{
    /**
     * @return bool
     */
    public function publicIsPost()
    {
        return $this->isPost();
    }

    /**
     * @param null|string|numeric $sKey
     *
     * @return string|Parameters
     */
    public function publicGetPost($sKey = null)
    {
        return $this->getPost($sKey);
    }

    /**
     * @param null|string|numeric $sKey
     *
     * @return mixed
     */
    public function publicGetFiles($sKey = null)
    {
        return $this->getFiles($sKey);
    }

    /**
     * @param array $aParams
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function publicReturnAsJson(array $aParams)
    {
        return $this->returnAsJson($aParams);
    }

    /**
     * @return \BaseModule\Controller\Doctrine\ORM\EntityManager
     */
    public function publicGetEm()
    {
        return $this->getEm();
    }

    /**
     * @param string $sRepoName
     *
     * @return \Doctrine\ORM\EntityRepository
     */
    public function publicGetRepo($sRepoName)
    {
        return $this->getRepo($sRepoName);
    }

    /**
     * @return \BaseModule\Logger\ErrorHandler
     */
    public function publicGetErrorHandler()
    {
        return $this->getErrorHandler();
    }

    /**
     * @param string $sKey
     *
     * @return array|string
     */
    public function publicGetRouteParam($sKey = null)
    {
        return $this->getRouteParam($sKey);
    }

    /**
     * @return \BaseModule\Helper\AppConfig
     */
    public function publicGetAppConfig()
    {
        return $this->getAppConfig();
    }
}