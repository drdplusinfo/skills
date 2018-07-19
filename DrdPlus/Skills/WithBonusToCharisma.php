<?php
declare(strict_types = 1);

namespace DrdPlus\Skills;

interface WithBonusToCharisma
{
    /**
     * @return int
     */
    public function getBonusToCharisma(): int;
}