<?php

namespace Pan\Dice;

use Anax\Response\ResponseUtility;
use Anax\DI\DIMagic;
use PHPUnit\Framework\TestCase;

/**
 * Test the controller like it would be used from the router,
 * simulating the actual router paths and calling it directly.
 */
class DiceControllerTest extends TestCase
{
    private $controller;
    private $app;

    protected function setUp(): void
    {
        global $di;
        // Init service container $di to contain $app as a service
        $di = new DIMagic();
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");
        $app = $di;
        $this->app = $app;
        $di->set("app", $app);
        $this->controller = new DiceGameController();
        $this->controller->setApp($app);
    }

    /**
     * Call the controller index action.
     */
    public function testIndexAction()
    {
        $res = $this->controller->indexAction();
        $this->assertIsString($res);
        $this->assertStringEndsWith("active", $res);
    }


    /**
     * Call the controller init action.
     */
    public function testInitAction()
    {
        $res = $this->controller->initAction();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Call the controller play action GET.
     */
    public function testPlayActionGet()
    {
        $res = $this->controller->playActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Call the controller play action Post.
     */
    public function testPlayActionPost()
    {
        $this->app->request->setGlobals([
            "post" => [
                "roll" => 1,
                "maroll" => 1,
                "save" => 1,
                "reset" => 1
            ]
        ]);
        $res = $this->controller->playActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }
}
