<div class="form">
<h1>Welcome to the Dice100 Game!</h1>

<h2>Result: <?= $winner ?></h2>
<p><label>You: <?= $total1 ?> Points</label><progress class="points" value=<?= $total1 ?> max=100></progress></p>
<p><label>Marvin: <?= $total2 ?> Points</label><progress class="points" value=<?= $total2 ?> max=100></progress></p>

<div>
    <p>NOW <?= $who ?> Play</p>
    <pre><?= $getText ?></pre>
    <p>Pot: <?= $sum ?>. This roll: <?= $thisroll ?></p>
</div>
<div>
    <form method="post">
            <input class="<?= $classname1 ?>" type="submit" name="roll" value="Roll">
            <input class="<?= $classname2 ?>" type="submit" name="maroll" value="Marvin Roll">
            <input class=<?= $classname3 ?> type="submit" name="save" value="Save">
            <input class="bluebutton" type="submit" name="reset" value="Reset">
    </form>
</div>
</div>
