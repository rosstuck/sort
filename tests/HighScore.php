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
    private $points;

    /**
     * @var DateTime
     */
    private $date;

    public function __construct($name, $score, DateTime $date)
    {
        $this->name = $name;
        $this->points = $score;
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
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
    }
}
