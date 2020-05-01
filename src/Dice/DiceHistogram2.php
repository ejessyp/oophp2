<?php

namespace Pan\Dice;

/**
 * A dice which has the ability to present data to be used for creating
 * a histogram.
 */
class DiceHistogram2 extends Dice implements HistogramInterface
{
    use HistogramTrait2;


    /**
     * Get max value for the histogram.
     *
     * @return int with the max value.
     */
    public function getHistogramMax()
    {
        return 6;
    }


    public function resetSerie()
    {
        $this-> serie = [];
    }


    /**
     * Roll the dice, remember its value in the serie and return
     * its value.
     *
     * @return int the value of the rolled dice.
     */
    public function roll()
    {
        $this->serie[] = parent::roll();
        return $this->getLastRoll();
    }

    /**
    * Get the sum of all dices.
    *
    * @return int as the sum of all dices.
    */
    public function sum()
    {
        $sum = 0;
        for ($i = 0; $i < count($this->serie); $i++) {
            $sum += $this->serie[$i];
        }
        return $sum;
    }
}
