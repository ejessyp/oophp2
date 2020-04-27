<?php

namespace Pan\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Dice.
 */
class DiceTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties.
     */
    public function testCreateObject()
    {
        $dice = new Dice();
        $this->assertInstanceOf("\Pan\Dice\Dice", $dice);
        $res = $dice->getSides();
        $exp = 6;
        $this->assertEquals($exp, $res);
    }
    /**
     * Construct object and do the roll
     */
    public function testRoll()
    {
        $dice = new Dice();
        $this->assertInstanceOf("\Pan\Dice\Dice", $dice);
        $dice->roll();
        $res = $dice->getLastRoll();

        $this->assertLessThan(7, $res);
    }
}
