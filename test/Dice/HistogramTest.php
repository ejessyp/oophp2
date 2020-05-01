<?php

namespace Pan\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Histogram.
 */
class HistogramTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties and roll dices
     */
    public function testGetSerieHistogram()
    {
        $dice = new DiceHistogram2();
        for ($i = 0; $i < 6; $i++) {
            $dice->roll();
        }
        $histogram = new Histogram();
        $this->assertInstanceOf("\Pan\Dice\Histogram", $histogram);
        $this->assertInstanceOf("\Pan\Dice\DiceHistogram2", $dice);
        $histogram->injectData($dice);

        $res = $histogram->getSerie();

        $exp = $dice->getHistogramSerie();

        $this->assertEquals($exp, $res);
    }


    public function testGetAsTextHistogram()
    {
        $dice = new DiceHistogram2();
        for ($i = 0; $i < 6; $i++) {
            $dice->roll();
        }
        $histogram = new Histogram();
        $this->assertInstanceOf("\Pan\Dice\Histogram", $histogram);
        $this->assertInstanceOf("\Pan\Dice\DiceHistogram2", $dice);
        $histogram->injectData($dice);

        $res = $histogram->getAsText();

        $exp = $dice->getHistogramSerie();
        $arr = array_count_values($exp);
        $arr2 = $arr;
        for ($i=1; $i <= 6; $i++) {
            if (! array_key_exists("$i", $arr)) {
                $arr2["$i"] = 0;
            } else {
                $arr2["$i"] = $arr["$i"];
            }
        }
        ksort($arr2);
        $histo = "";
        foreach ($arr2 as $x => $xValue) {
            $histo .= $x . ": " . str_repeat("*", $xValue) . "<br>";
        }

        $this->assertEquals($histo, $res);
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
