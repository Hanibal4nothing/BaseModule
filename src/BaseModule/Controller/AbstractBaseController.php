<?php
/**
 * BaseController.php
 *
 * @copyright Felix Buchheim
 * @version   $Id: $
 */

namespace BaseModule\Controller;

use BaseModule\Helper\AppConfig;
use BaseModule\Logger\ErrorHandler;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Doctrine\ORM\EntityManager;

/**
 * the sense of this abstract-controller is to:
 * - simplify the syntax in the controller
 * - get autocomplete for zf2 magic methods
 * - save space
 *
 * @package   BaseModule\Controller
 * @copyright Felix Buchheim
 * @author    Felix Buchheim <hanibal4nothing@gmail.com>
 */
abstract class AbstractBaseController extends AbstractActionController
{
    /**
     * check if the request is a post
     *
     * @return boolean
     */
    protected function isPost()
    {
        return $this->getRequest()->isPost();
    }

    /**
     * Return the current $_POST
     *
     * @param string|null $sKey
     * @throws \InvalidArgumentException
     *
     * @return mixed
     */
    protected function getPost($sKey = null)
    {
        $this->checkIfKeyIsValid($sKey);
        if ($sKey === null) {
            $mReturn = $this->getRequest()->getPost();
        }
        else {
            $mReturn = $this->getRequest()->getPost($sKey);
        }

        return $mReturn;
    }

    /**
     * Return the current $_FILES
     *
     * @param string|null $sKey
     *
     * @return mixed
     */
    protected function getFiles($sKey = null)
    {
        $this->checkIfKeyIsValid($sKey);
        if ($sKey === null) {
            $mReturn = $this->getRequest()->getFiles();
        }
        else {
            $mReturn = $this->getRequest()->getFiles($sKey);
        }

        return $mReturn;
    }

    /**
     * Convert the params to json
     *
     * @param array $aParams
     *
     * @return JsonModel
     */
    protected function returnAsJson(array $aParams)
    {
        return new JsonModel($aParams);
    }

    /**
     * return the EntityManager
     *
     * @return EntityManager
     */
    protected function getEm()
    {
        return $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    }

    /**
     * return the doctrine repository with the given name
     *
     * @param string $sRepoName
     *
     * @throws \UnexpectedValueException
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getRepo($sRepoName)
    {
        $oEntityManager = $this->getEm();
        if (false === isset($oEntityManager)){
            throw new \UnexpectedValueException('Doctrine is not loaded');
        }
        return $this->getEm()->getRepository($sRepoName);
    }

    /**
     * return the ErrorHandler
     *
     * @return ErrorHandler
     */
    protected function getErrorHandler()
    {
        return $this->getServiceLocator()->get('BaseModule\Logger\ErrorHandler');
    }

    /**
     * return the routeParam
     *
     * @param string $sKey
     *
     * @return array|string
     */
    protected function getRouteParam($sKey = null)
    {
        return ($sKey === null ? $this->params()->fromRoute() : $this->params()->fromRoute($sKey));
    }

    /**
     * @return AppConfig
     */
    protected function getAppConfig()
    {
        return $this->getServiceLocator()->get('BaseModule\Helper\AppConfig');
    }

    /**
     * check if the given key is numeric or a string
     *
     * @param string $sKey
     * @throws \InvalidArgumentException
     *
     * @return bool
     */
    private function checkIfKeyIsValid($sKey)
    {
        if (true === isset($sKey) and false === is_string($sKey) and false === is_numeric($sKey)) {
            throw new \InvalidArgumentException('key have to be string or numeric. ' . gettype($sKey) . ' given!');
        }

        return true;
    }
} 