<?php

namespace Pan\Movie;

use Anax\Response\ResponseUtility;
use Anax\DI\DIMagic;
use PHPUnit\Framework\TestCase;

/**
 * Test the controller like it would be used from the router,
 * simulating the actual router paths and calling it directly.
 */
class MovieControllerTest extends TestCase
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
        $this->controller = new MovieController();
        $this->controller->setApp($app);
        $this->controller->initialize();
    }

    /**
     * Call the controller index action.
     */
    public function testIndexAction()
    {

        $res = $this->controller->indexAction();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }



    /**
     * test Search title Action Get.
     */
    public function testSearchtitleActionGet()
    {
        $this->app->request->setGlobals([
            "get" => [
                "searchTitle" => 1,
                "maroll" => 1,
                "save" => 1,
                "reset" => 1
            ]
        ]);
        $res = $this->controller->searchtitleActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Call the controller Search year Action Get.
     */
    public function testSearchyearActionGet()
    {
        $this->app->request->setGlobals([
            "get" => [
                "year1" => 1,
                "year2" => 1
            ]
        ]);
        $res = $this->controller->searchyearActionGet();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }

    /**
     * Call the controller admin action Post.
     */
    public function testAdminActionPost()
    {
        $this->app->request->setGlobals([
            "post" => [
                "doDelete" => 1,
                "doAdd" => 1,
                "doEdit" => 1,
                "movieId" => 1
            ]
        ]);
        $res = $this->controller->adminActionPost();
        $this->assertInstanceOf(ResponseUtility::class, $res);
    }
}
