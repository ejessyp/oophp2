<?php
/**
* Create routes using $app programming style.
*/
//var_dump(array_keys(get_defined_vars()));


/**
* start a  text filter.
*/
$app->router->get("textfilter/filter", function () use ($app) {
    // Init a game
    $textfilter = new Pan\TextFilter\MyTextFilter();
    $title = "My text filter";
    $dir = __DIR__;
    $tempdir = str_replace("router", "htdocs", $dir);
    $text = file_get_contents($tempdir . "/text/sample.md");
    $html = $textfilter->parse($text, ["markdown"]);

    $text1 = file_get_contents($tempdir . "/text/bbcode.txt");
    $html1 = $textfilter->parse($text1, ["bbcode", "link"]);

    $text2 = file_get_contents($tempdir . "/text/clickable.txt");
    $html2 = $textfilter->parse($text2, ["link"]);

    $text3 = file_get_contents($tempdir . "/text/nl2br.txt");

    $html3 = $textfilter->parse($text3, ["markdown", "nl2br"]);
    $data = [
        "text" => $text ?? null,
        "html" => $html ?? null,
        "text1" => $text1 ?? null,
        "html1" => $html1 ?? null,
        "text2" => $text2 ?? null,
        "html2" => $html2 ?? null,
        "text3" => $text3 ?? null,
        "html3" => $html3 ?? null
    ];
    $app->page->add("textfilter/index", $data);
    return $app->page->render([
        "title" => $title,
    ]);
});
