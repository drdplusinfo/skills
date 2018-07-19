<?php
declare(strict_types = 1);

namespace DrdPlus\Skills;

interface WithBonusToMovementSpeed
{
    /**
     * @return int
     */
    public function getBonusToMovementSpeed(): int;
}