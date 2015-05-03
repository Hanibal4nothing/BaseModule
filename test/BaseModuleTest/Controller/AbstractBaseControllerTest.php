<?php
/**
 * <SHORT Description>
 *
 * <LONG Description>
 *
 * @copyright Copyright (c) 2015 Hanibal
 * @package   BaseModuleTest\Controller
 * @author    Felix Buchheim -> hanibal4nothing@gmail.com
 * @author    Hanibal
 * @version   $Id: $
 */

namespace BaseModuleTest\Controller;

use BaseModuleTest\Dummy\BaseControllerDummy;
use Zend\Http\Request;
use Zend\Mvc\Router\Http\RouteMatch;
use Zend\Stdlib\Parameters;
use Zend\View\Model\JsonModel;

/**
 * test for AbstractBaseController
 *
 * @copyright Copyright (c) ${YEAR} Hanibal
 * @package   BaseModuleTest\Controller
 * @author    Felix Buchheim -> hanibal4nothing@gmail.com
 * @author    Hanibal
 * @version   $Id: $
 */
class AbstractBaseControllerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var BaseControllerDummy
     */
    protected $controller;

    /**
     * setUp
     */
    public function setUp()
    {
        $this->controller = new BaseControllerDummy();
    }

    /**
     * tearDown
     */
    public function tearDown()
    {
        unset($this->controller);
    }

    /**
     * @covers AbstractBaseController::isPost
     */
    public function testIsPost()
    {
        $this->assertFalse($this->controller->publicIsPost());

        $oRequest = new Request();
        $this->controller->getEvent()->setRequest($oRequest);
        $oRequest->setMethod(Request::METHOD_POST);
        $this->controller->getEvent()->setRouteMatch(new RouteMatch(array('abc')))->setRequest($oRequest);
        $this->controller->dispatch($this->controller->getEvent()->getRequest());
        $this->assertTrue($this->controller->publicIsPost());

        $oRequest->setMethod(Request::METHOD_PUT);
        $this->controller->dispatch($oRequest);
        $this->assertFalse($this->controller->publicIsPost());

        $oRequest->setMethod(Request::METHOD_DELETE);
        $this->controller->dispatch($oRequest);
        $this->assertFalse($this->controller->publicIsPost());

        $oRequest->setMethod(Request::METHOD_PATCH);
        $this->controller->dispatch($oRequest);
        $this->assertFalse($this->controller->publicIsPost());

        $oRequest->setMethod(Request::METHOD_GET);
        $this->controller->dispatch($oRequest);
        $this->assertFalse($this->controller->publicIsPost());
    }

    /**
     * @covers AbstractBaseController::getPost
     */
    public function testGetPost()
    {
        $oResult = $this->controller->publicGetPost();
        $this->assertTrue($oResult instanceof Parameters);
        $aAsArray = $oResult->toArray();
        $this->assertTrue(empty($aAsArray));

        $aParams = array(1, 'abc' => 'cdf', '2' => 123);
        $oRequest = new Request();
        $oRequest->setPost(new Parameters($aParams));
        $this->controller->getEvent()->setRouteMatch(new RouteMatch(array('abc')))->setRequest($oRequest);
        $this->controller->dispatch($this->controller->getEvent()->getRequest());

        $oResult = $this->controller->publicGetPost();
        $this->assertTrue($oResult instanceof Parameters);
        $aAsArray = $oResult->toArray();
        $this->assertEquals(1, $oResult->{0});
        $this->assertEquals('cdf', $oResult->abc);
        $this->assertEquals(123, $oResult->{'2'});

        $this->assertEquals(3, count($aAsArray));
        $this->assertEquals($aParams, $aAsArray);

        $this->assertEquals(1, $this->controller->publicGetPost(0));
        $this->assertEquals('cdf', $this->controller->publicGetPost('abc'));
        $this->assertEquals(123, $this->controller->publicGetPost('2'));

        $this->setExpectedException('InvalidArgumentException', 'key have to be string or numeric.');
        $this->controller->publicGetPost(new Parameters());
    }

    /**
     * @covers AbstractBaseController::returnAsJson
     */
    public function testReturnAsJson()
    {
        $oResult = $this->controller->publicReturnAsJson(array('abc' => 'cdf'));

        $this->assertTrue($oResult instanceof JsonModel);
        $this->assertEquals('{"abc":"cdf"}', $oResult->serialize());
        $oResult = $this->controller->publicReturnAsJson(array('test' => new Request()));
        $this->assertEquals('{"test":{}}', $oResult->serialize());
    }

}