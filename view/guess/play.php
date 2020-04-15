<div class="form">
<h1>Guess my number</h1>
<p>Guess a number between 1 and 100, you have <?= $tries ?>  tries left.</p>

<form method="post">
        <input type="number" name="number">
        <input class="submit_button" type="submit" name="guess" value="Make a Guess">
        <input class="submit_button" type="submit" name="reset" value="Reset Game">
        <input class="submit_button" type="submit" name="cheat" value="Cheat">
</form>
</div>
<?php if ($guess) : ?>
<p>Your guess <?= $number;?> is <?= $res?>. </p>
<?php endif; ?>

<?php if ($cheat) : ?>
<p>CHEAT: secret number is  <?= $secret ?>. </p>
<?php endif; ?>
