<?php
/**
 * Load the dicegame as a controller class.
 */
return [
    "routes" => [
        [
            "info" => "Dice game.",
            "mount" => "dice3",
            "handler" => "\Pan\Dice\DiceGameController",
        ],
    ]
];
