<?php
declare(strict_types = 1);

namespace DrdPlus\Skills;

interface WithBonusToIntelligence
{
    /**
     * @return int
     */
    public function getBonusToIntelligence(): int;
}