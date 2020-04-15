<?php
/**
 * Show off the autoloader.
 */
include(__DIR__ . "/autoload.php");
include(__DIR__ . "/config.php");
include(__DIR__ . "/src/function.php");

include("view/form.php");
$cheat = $_POST["cheat"] ?? null;
$reset = $_POST["reset"] ?? null;
$guess = $_POST["guess"] ?? null;
$number = $_POST["number"] ?? null;


if (!isset($_SESSION["game"])) {
    $_SESSION["game"] = new Guess();
}
$game = $_SESSION["game"];

if (count($_POST) > 0) {
    if (!empty($_POST["cheat"])) {
        echo "The secret number is: <strong>". $game->number(). "</strong>.";
    } elseif (!empty($_POST["guess"])) {
        $guesshumber =  intval($_POST["number"]);
        try {
            echo $game->makeGuess($guesshumber);
            if ($game->tries() <= 0) {
                sessionDestroy();
            }
        } catch (GuessException $e) {
             echo get_class($e). " <br> Number must range from 1 to 100. ";
        }
    } elseif (!empty($_POST["reset"])) {
        sessionDestroy();
        header('Location: index.php');
    }
}
