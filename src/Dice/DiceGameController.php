<?php

// namespace Anax\Controller;
namespace Pan\Dice;

use Anax\Commons\AppInjectableInterface;
use Anax\Commons\AppInjectableTrait;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * A sample controller to show how a controller class can be implemented.
 * The controller will be injected with $app if implementing the interface
 * AppInjectableInterface, like this sample class does.
 * The controller is mounted on a particular route and can then handle all
 * requests for that mount point.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class DiceGameController implements AppInjectableInterface
{
    use AppInjectableTrait;



    /**
     * @var string $db a sample member variable that gets initialised
     */
    private $db = "not active";



    // /**
    //  * The initialize method is optional and will always be called before the
    //  * target method/action. This is a convienient method where you could
    //  * setup internal properties that are commonly used by several methods.
    //  *
    //  * @return void
    //  */
    // public function initialize() : void
    // {
    //     // Use to initialise member variables.
    //     $this->db = "active";
    //
    //     // Use $this->app to access the framework services.
    // }

    /**
     * This is the index method action, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return string
     */
    public function indexAction() : string
    {
        // Deal with the action and return a response.
        return __METHOD__ . ", \$db is {$this->db}";
    }

    /**
     * This is the init method action, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return object
     */
    public function initAction() : object
    {
        // Init a game
        $session = $this->app->session;
        $response = $this->app->response;

        $player = new DiceHistogram2();
        $marvin = new DiceHistogram2();
        $session->set("player", $player);
        $session->set("marvin", $marvin);
        $session->set("total1", 0);
        $session->set("total2", 0);
        $session->set("who", "You");
        $session->set("winner", "");
        $session->set("classname1", "bluebutton");
        $session->set("classname2", "hide");
        $session->set("classname3", "greybutton");
        return $response->redirect("dice3/play");
    }

    /**
     * This is the play method get action, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return object
     */
    public function playActionGet() : object
    {
        $title = "Play the Dice Game";
        $session = $this->app->session;
        $total1 = $session->get("total1");
        $total2 = $session->get("total2");
        $who = $session->get("who");
        $class = $session->get("class");
        $sum = $session->get("sum");
        $thisroll = $session->get("thisroll");
        $classname1 = $session->get("classname1");
        $classname2 = $session->get("classname2");
        $classname3 = $session->get("classname3");
        $winner = $session->get("winner");
        $getText = $session->get("getText");
        //
        // $total1 = $_SESSION["total1"] ?? 0;
        // $total2 = $_SESSION["total2"] ?? 0;
        // $who = $_SESSION["who"];
        // $class = $_SESSION["class"] ?? [];
        // $sum = $_SESSION["sum"] ?? 0;
        // $thisroll = $_SESSION["thisroll"] ?? 0;
        // $classname1 = $_SESSION["classname1"] ?? null;
        // $classname2 = $_SESSION["classname2"] ?? null;
        // $classname3 = $_SESSION["classname3"] ?? null;
        // $winner = $_SESSION["winner"] ?? null;
        $data = [
            "class"=> $class ?? [],
            "getText"=> $getText ?? "",
            "total1"=> $total1 ?? 0,
            "total2"=> $total2 ?? 0,
            "who"=> $who ?? null,
            "winner"=> $winner ?? null,
            "sum"=> $sum ?? 0,
            "thisroll"=> $thisroll ?? 0,
            "classname1"=> $classname1 ?? null,
            "classname2"=> $classname2 ?? null,
            "classname3"=> $classname3 ?? null
        ];
        $this->app->page->add("dice3/play", $data);
        // $this->app->page->add("dice3/debug");

        return $this->app->page->render([
            "title" => $title,
        ]);
    }


    /**
     * This sample method action it the handler for route:
     * POST mountpoint/play
     *
     * @return object
     */
    public function playActionPost() : object
    {
        // Incoming variables
        $request = $this->app->request;
        $response = $this->app->response;
        $session = $this->app->session;
        $roll = $request->getPost("roll");
        $maroll = $request->getPost("maroll");
        $save = $request->getPost("save");
        $reset = $request->getPost("reset");
        $player = $session->get("player");
        $marvin = $session->get("marvin");
        $sum = $session->get("sum");
        // player roll
        if ($roll) {
            $rolls = 4;
            $player->resetSerie();
            for ($i = 0; $i < $rolls; $i++) {
                $player->roll();
            }
            $histogram = new Histogram();
            $histogram->injectData($player);
            $values = $histogram->getSerie();
            $getText =$histogram->getAsText();
            $thisroll = $player->sum();
            $sum += $thisroll;
            $session->set("getText", $getText);
            $session->set("thisroll", $thisroll);
            $session->set("sum", $sum);
            $session->set("classname3", "bluebutton");
            if (in_array(1, $values)) {
                $session->set("classname1", "hide");
                $session->set("classname2", "bluebutton");
                $session->set("classname3", "greybutton");
                $session->set("who", "Marvin");
                $session->set("sum", "0");
            }
        }

        // Marvin roll
        if ($maroll) {
            $rolls = 4;
            $marvin->resetSerie();
            for ($i = 0; $i < $rolls; $i++) {
                $marvin->roll();
            }
            $histogram = new Histogram();
            $histogram->injectData($marvin);
            $values = $histogram->getSerie();
            $getText =$histogram->getAsText();
            $thisroll = $marvin->sum();
            $sum += $thisroll;

            $session->set("getText", $getText);
            $session->set("thisroll", $thisroll);
            $session->set("sum", $sum);
            if (in_array(1, $values)) {
                $session->set("classname1", "bluebutton");
                $session->set("classname2", "hide");
                $session->set("classname3", "greybutton");
                $session->set("who", "You");
                $session->set("sum", "0");
            }
            if ($thisroll > 12) {
                $temp = $session->get("sum");
                $temp1 = $session->get("total2");
                $total2 = $temp1 + $temp;
                $session->set("total2", $total2);
                $session->set("classname1", "bluebutton");
                $session->set("classname2", "hide");
                // $session->set("getText", "");
                $session->set("who", "You");
                $session->set("sum", 0);
                $session->set("thisroll", 0);
                if ($session->get("total2") >= 100) {
                    $session->set("winner", "Marvin Wins!");
                    $session->set("classname1", "greybutton");
                    $session->set("classname2", "greybutton");
                    $session->set("classname3", "greybutton");
                };
            }
        }

        // save the pot
        if ($save && $session->get("who") == "You") {
            $temp = $session->get("sum");
            $temp1 = $session->get("total1");
            $total1 = $temp1 + $temp;
            $session->set("total1", $total1);
            $session->set("classname1", "hide");
            $session->set("classname2", "bluebutton");
            $session->set("classname3", "greybutton");
            $session->set("getText", "");
            $session->set("who", "Marvin");
            $session->set("sum", 0);
            $session->set("thisroll", 0);

            if ($session->get("total1") >= 100) {
                $session->set("winner", "You Win!");
                $session->set("classname1", "greybutton");
                $session->set("classname2", "greybutton");
                $session->set("classname3", "greybutton");
            };
        }
        if ($reset) {
            $session->destroy();

            return $response->redirect("dice3/init");
        }

        return $response->redirect("dice3/play");
    }
}
