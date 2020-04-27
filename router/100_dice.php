<?php
/**
* Create routes using $app programming style.
*/
/**
* start a dice game.
*/
$app->router->get("dice/init", function () use ($app) {
    // Init a game
    $player = new Pan\Dice\DiceHand();
    $marvin = new Pan\Dice\DiceHand();
    $_SESSION["player"] = $player;
    $_SESSION["marvin"] = $marvin;
    $_SESSION["total1"] = 0;
    $_SESSION["total2"] = 0;
    $_SESSION["who"] = "You";
    $_SESSION["winner"] = "";
    $_SESSION["classname1"] = "bluebutton";
    $_SESSION["classname2"] = "hide";
    $_SESSION["classname3"] = "greybutton";
    return $app->response->redirect("dice/play");
});

/**
* Play a game, showing game status, rendered within the standard page layout.
*/
$app->router->get("dice/play", function () use ($app) {
    $title = "Play the Dice Game";
    $total1 = $_SESSION["total1"] ?? 0;
    $total2 = $_SESSION["total2"] ?? 0;
    $who = $_SESSION["who"];
    $class = $_SESSION["class"] ?? [];
    $sum = $_SESSION["sum"] ?? 0;
    $thisroll = $_SESSION["thisroll"] ?? 0;
    $classname1 = $_SESSION["classname1"] ?? null;
    $classname2 = $_SESSION["classname2"] ?? null;
    $classname3 = $_SESSION["classname3"] ?? null;
    $winner = $_SESSION["winner"] ?? null;
    $data = [
        "class"=> $class ?? null,
        "total1"=> $total1 ?? 0,
        "total2"=> $total2 ?? 0,
        "who"=> $who ?? null,
        "winner"=> $winner ?? null,
        "sum"=> $sum ?? 0,
        "thisroll"=> $thisroll ?? 0,
        "classname1"=> $classname1,
        "classname2"=> $classname2,
        "classname3"=> $classname3
    ];
    $app->page->add("dice/play", $data);
    // $app->page->add("dice/debug");

    return $app->page->render([
        "title" => $title,
    ]);
});

/**
* Play a game, roll dice and save the pot and reset the game
*/
$app->router->post("dice/play", function () use ($app) {
    $title = "Play the Dice Game";

    // Incoming variables
    $roll = $_POST["roll"] ?? null;
    $maroll = $_POST["maroll"] ?? null;
    $save = $_POST["save"] ?? null;
    $reset = $_POST["reset"] ?? null;

    $player = $_SESSION["player"];
    $marvin = $_SESSION["marvin"];
    $sum = $_SESSION["sum"];

    // player roll
    if ($roll) {
        $player->roll();
        $values = $player->values();
        $class = $player->graphic();
        $thisroll = $player->sum();
        $sum += $thisroll;

        $_SESSION["class"] = $class;
        $_SESSION["thisroll"] = $thisroll;
        $_SESSION["sum"] = $sum;
        $_SESSION["classname3"] = "bluebutton";

        if (in_array(1, $values)) {
            $_SESSION["classname1"] = "hide";
            $_SESSION["classname2"] = "bluebutton";
            $_SESSION["classname3"] = "greybutton";
            $_SESSION["who"] = "Marvin";
            $_SESSION["sum"] = 0;
        }
    }

    // Marvin roll
    if ($maroll) {
        $marvin->roll();
        $values = $marvin->values();
        $thisroll = $marvin->sum();
        $sum += $thisroll;
        $class = $marvin->graphic();
        $_SESSION["class"] = $class;
        $_SESSION["sum"] = $sum;
        $_SESSION["thisroll"] = $thisroll;

        if (in_array(1, $values)) {
            $_SESSION["classname1"] = "bluebutton";
            $_SESSION["classname2"] = "hide";
            $_SESSION["classname3"] = "greybutton";
            $_SESSION["who"] = "You";
            $_SESSION["sum"] = 0;
        }

        if ($thisroll > 12) {
            $_SESSION["total2"] += $_SESSION["sum"];
            $_SESSION["classname1"] = "bluebutton";
            $_SESSION["classname2"] = "hide";
            $_SESSION["who"] = "You";
            $_SESSION["class"] = [];
            $_SESSION["sum"] = 0;
            $_SESSION["thisroll"] = 0;
            if ($_SESSION["total2"] > 100) {
                $_SESSION["winner"] = "Marvin Wins!";
                $_SESSION["classname1"] = "greybutton";
                $_SESSION["classname2"] = "greybutton";
                $_SESSION["classname3"] = "greybutton";
            };
        }
    }

    // save the pot
    if ($save && $_SESSION["who"] == "You") {
        $_SESSION["total1"] += $_SESSION["sum"];
        $_SESSION["classname1"] = "hide";
        $_SESSION["classname2"] = "bluebutton";
        $_SESSION["classname3"] = "greybutton";
        $_SESSION["who"] = "Marvin";
        $_SESSION["class"] = [];
        $_SESSION["sum"] = 0;
        $_SESSION["thisroll"] = 0;
        if ($_SESSION["total1"] > 100) {
            $_SESSION["winner"] = "You Win!";
            $_SESSION["classname1"] = "greybutton";
            $_SESSION["classname2"] = "greybutton";
            $_SESSION["classname3"] = "greybutton";
        };
    }

    // reset the game
    if ($reset) {
        $_SESSION = [];
        session_destroy();
        return $app->response->redirect("dice/init");
    }

    return $app->response->redirect("dice/play");
});
