<?php
declare(strict_types = 1);

namespace DrdPlus\Skills;

interface WithBonus
{
    /**
     * @return int
     */
    public function getBonus(): int;
}