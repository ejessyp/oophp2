<?php
/**
 * Load the movie as a controller class.
 */
return [
    "routes" => [
        [
            "info" => "Movie Megamic.",
            "mount" => "movie",
            "handler" => "\Pan\Movie\MovieController",
        ],
    ]
];
