<?php
namespace Pan\Guess;

/**
* Guess my number, a class supporting the game through GET, POST and SESSION.
*/
class Guess
{
    /**
    * @var int $number   The current secret number.
    * @var int $tries    Number of tries a guess has been made.
    */
    private $number;
    private $tries;

    /**
    * Constructor to initiate the object with current game settings,
    * if available. Randomize the current number if no value is sent in.
    *
    * @param int $number The current secret number, default -1 to initiate
    *                    the number from start.
    * @param int $tries  Number of tries a guess has been made,
    *                    default 6.
    */
    public function __construct(int $number = -1, int $tries = 6)
    {
        $this -> tries = $tries;
        if ($number == -1) {
            $this -> number = mt_rand(1, 100);
        } else {
            $this -> number = $number;
        }
    }

    /**
    * Randomize the secret number between 1 and 100 to initiate a new game.
    *
    * @return void
    */
    public function random()
    {
        $secret = mt_rand(1, 100);
        $this -> number = $secret;
        $this -> __construct($secret);
    }


    /**
    * Get number of tries left.
    *
    * @return int as number of tries made.
    */

    public function tries()
    {
        return $this -> tries;
    }

    /**
    * Get the secret number.
    *
    * @return int as the secret number.
    */

    public function number()
    {
        return $this -> number;
    }

    /**
    * Make a guess, decrease remaining guesses and return a string stating
    * if the guess was correct, too low or to high or if no guesses remains.
    *
    * @throws GuessException when guessed number is out of bounds.
    *
    * @return string to show the status of the guess made.
    */
    public function makeGuess($number1)
    {
        $this -> tries --;

        $number = intval($number1);

        if ($number > 100 or $number < 1) {
            throw new GuessException();
        } elseif ($number > $this -> number) {
            if ($this -> tries <= 0) {
                return "Your have tried 6 times and you lost! Try again!";
            } else {
                return "TOO HIGH";
            }
        } elseif ($number < $this -> number) {
            if ($this -> tries <= 0) {
                return "Your have tried 6 times and you lost! Try again!";
            } else {
                return "TOO LOW";
            }
        } elseif ($number == $this -> number) {
            // if ( $this -> tries > 0) {
            //     session_destroy();
            // }
            return "CORRECT";
        }
    }
}
