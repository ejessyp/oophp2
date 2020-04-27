<?php

namespace Pan\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class DiceHand.
 */
class DiceHandTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties and roll dices
     */
    public function testRollDices()
    {
        $hand = new DiceHand();
        $this->assertInstanceOf("\Pan\Dice\DiceHand", $hand);

        $hand->roll();
        $res = $hand->values();
        $sum = $hand->sum();
        $exp = 0;
        for ($i = 0; $i < count($res); $i++) {
            $exp += $res[$i];
        }
        $this->assertEquals($exp, $sum);
    }

    /**
     * Construct object and verify that the object has the expected
     * properties and roll dices
     */
    public function testGetSum()
    {
        $hand = new DiceHand();
        $this->assertInstanceOf("\Pan\Dice\DiceHand", $hand);

        $hand->roll();
        $res = $hand->sum();
        $exp = $hand->values();
        $sum = 0;
        for ($i = 0; $i < count($exp); $i++) {
            $sum += $exp[$i];
        }
        $this->assertEquals($sum, $res);
    }

    /**
     * Construct object and verify that the object has the expected
     * properties and roll dices and get average
     */
    public function testGetAverage()
    {
        $hand = new DiceHand();
        $this->assertInstanceOf("\Pan\Dice\DiceHand", $hand);

        $hand->roll();
        $sum = $hand->sum();
        $avg = $hand->average();
        echo $avg;

        $this->assertEquals($sum / 4, $avg);
    }

    /**
     * Construct object and verify that the object has the expected
     * properties and roll dices and get Graphic
     */
    public function testGetGraphic()
    {
        $hand = new DiceHand();
        $this->assertInstanceOf("\Pan\Dice\DiceHand", $hand);

        $hand->roll();
        $graphic = $hand->graphic();
        $values = $hand->values();
        $new = array_map(function ($graph) {
            return(str_replace("dice-", "", $graph));
        }, $graphic);
        $this->assertEquals($values, $new);
    }
}
