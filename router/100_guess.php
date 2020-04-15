<?php
/**
* Create routes using $app programming style.
*/
//var_dump(array_keys(get_defined_vars()));


/**
* start a guess game.
*/
$app->router->get("guess/init", function () use ($app) {
    // Init a game
    $game = new Pan\Guess\Guess();
    $_SESSION["number"] = $game->number();
    $_SESSION["tries"] = $game->tries();

    return $app->response->redirect("guess/play");
});

/**
* Play a game, showing game status, rendered within the standard page layout.
*/
$app->router->get("guess/play", function () use ($app) {
    $title = "Play the Game";

    $tries = $_SESSION["tries"] ?? null;
    $res = $_SESSION["res"] ?? null;
    $guess = $_SESSION["guess"] ?? null;
    $secret = $_SESSION["number"] ?? null;
    $cheat = $_SESSION["cheat"] ?? null;

    $_SESSION["guess"] = null;
    $_SESSION["res"] = null;
    $_SESSION["cheat"] = null;

    $data = [
        "secret" => $secret ?? null,
        "tries" => $tries ?? null,
        "number" => $number ?? null,
        "cheat" => $cheat ?? null,
        "guess" => $guess ?? null,
        "res" => $res ?? null
    ];
    $app->page->add("guess/play", $data);
    $app->page->add("guess/debug");

    return $app->page->render([
        "title" => $title,
    ]);
});

/**
* Play a game, make a guess
*/
$app->router->post("guess/play", function () use ($app) {
    $title = "Play the Game";

    // Incoming variables
    $cheat = $_POST["cheat"] ?? null;
    $reset = $_POST["reset"] ?? null;
    $guess = $_POST["guess"] ?? null;
    $number = $_POST["number"] ?? null;

    $secret = $_SESSION["number"] ?? null;
    $tries = $_SESSION["tries"] ?? null;

    if ($guess) {
        $game = new Pan\Guess\Guess($secret, $tries);
        try {
            $res = $game->makeGuess($number);
            // if ($game->tries() <= 0) {
            //     session_destroy();
            // }
        } catch (Pan\Guess\GuessException $e) {
            $res = $number. " .<br><b> Number must range from 1 to 100 </b>";
        }
        $_SESSION["tries"] = $game->tries();
        $_SESSION["res"] = $res;
        $_SESSION["guess"] = $guess;
    }

    // Reset a game
    if ($reset) {
        $game = new Pan\Guess\Guess();
        $_SESSION["number"] = $game->number();
        $_SESSION["tries"] = $game->tries();
    }

    if ($cheat) {
        $_SESSION["cheat"] = $cheat;
    }

    return $app->response->redirect("guess/play");
});
