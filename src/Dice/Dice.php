<?php
namespace Pan\Dice;

/*
* Showing off a standard class with methods and properties.
*/
class Dice
{
    /**
    * @var int $sides how many sides of the dices.
    * @var int $lastRoll  Value of the last roll.
    */
    private $sides;
    //private $lastRoll;
    protected $lastRoll;

    /**
    * Constructor to create a Dice.
    *
    * @param int $sides how many sides of the dices.
    */
    public function __construct(int $sides = 6)
    {
        $this -> sides = $sides;
    }
    /**
    * roll the dice
    * @return int of numbers
    */
    public function roll()
    {
        $randomNumber = mt_rand(1, $this -> sides);
        $this -> lastRoll = $randomNumber;
        return $randomNumber;
    }

    public function getLastRoll()
    {
        return $this -> lastRoll;
    }
    /**
    * get the sides of dice
    * @return int of numbers
    */
    public function getSides()
    {
        return $this -> sides;
    }
}
