<?php
/**
 * Load the cms as a controller class.
 */
return [
    "routes" => [
        [
            "info" => "Content Mangement System",
            "mount" => "cms",
            "handler" => "\Pan\Cms\CmsController",
        ],
    ]
];
