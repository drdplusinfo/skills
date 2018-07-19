<?php
declare(strict_types = 1);

namespace DrdPlus\Skills;

interface WithBonusToKnack
{
    /**
     * @return int
     */
    public function getBonusToKnack(): int;
}