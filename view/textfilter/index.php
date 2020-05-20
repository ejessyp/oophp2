<!doctype html>
<html>
<meta charset="utf-8">
<title>Show off Markdown</title>

<h1>Showing off Markdown</h1>

<h2>Markdown source in sample.md</h2>
<pre><?= $text ?></pre>

<h2>Text formatted as HTML source</h2>
<pre><?= htmlentities($html) ?></pre>

<h2>Text displayed as HTML</h2>
<?= $html ?>


<h1>Showing off BBCode</h1>

<h2>Source in bbcode.txt</h2>
<pre><?= wordwrap(htmlentities($text1)) ?></pre>

<h2>Filter BBCode applied, source</h2>
<pre><?= wordwrap(htmlentities($html1)) ?></pre>

<h2>Filter BBCode applied, HTML (including nl2br())</h2>
<?= nl2br($html1) ?>


<h1>Showing off Clickable</h1>

<h2>Source in clickable.txt</h2>
<pre><?= wordwrap(htmlentities($text2)) ?></pre>

<h2>Source formatted as HTML</h2>
<?= $text2 ?>

<h2>Filter Clickable applied, source</h2>
<pre><?= wordwrap(htmlentities($html2)) ?></pre>

<h2>Filter Clickable applied, HTML</h2>
<?= $html2 ?>

<h1>Showing off nl2br</h1>

<pre><?= $html3 ?></pre>
