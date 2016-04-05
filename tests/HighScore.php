<?php

namespace Tuck\Sort\Tests;

use DateTime;

class HighScore
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $score;

    /**
     * @var DateTime
     */
    private $date;

    public function __construct(string $name, int $score, DateTime $date)
    {
        $this->name = $name;
        $this->score = $score;
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
    }
}
