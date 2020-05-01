<?php

namespace Pan\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class DiceHand.
 */
class DiceHistogram2Test extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties and roll dices
     */
    public function testRollDices()
    {
        $dice = new DiceHistogram2();
        $this->assertInstanceOf("\Pan\Dice\DiceHistogram2", $dice);

        $dice->roll();
        $res = $dice->getHistogramMax();
        $res1 = $dice->getHistogramMin();

        // $sum = $hand->sum();
        // $exp = 0;
        // for ($i = 0; $i < count($res); $i++) {
        //     $exp += $res[$i];
        // }
        $this->assertEquals(6, $res);
        $this->assertEquals(1, $res1);
    }

    /**
     * Construct object and verify that the object has the expected
     * properties and roll dices
     */
    public function testSum()
    {
        $dice = new DiceHistogram2();

        for ($i = 0; $i < 6; $i++) {
            $dice->roll();
        }

        $this->assertInstanceOf("\Pan\Dice\DiceHistogram2", $dice);

        $res = $dice->sum();
        $exp = $dice->getHistogramSerie();
        $sum = 0;
        for ($i = 0; $i < count($exp); $i++) {
            $sum += $exp[$i];
        }

        $this->assertEquals($sum, $res);
    }

    /**
     * Construct object and verify that the object has the expected
     * properties and roll dices
     */
    public function testResetSerie()
    {
        $dice = new DiceHistogram2();
        $this->assertInstanceOf("\Pan\Dice\DiceHistogram2", $dice);

        $dice->resetSerie();
        $res = $dice->getHistogramSerie();
        $this->assertEquals([], $res);
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
}
