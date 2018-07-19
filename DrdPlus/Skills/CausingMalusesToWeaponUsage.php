<?php
declare(strict_types = 1);

namespace DrdPlus\Skills;

interface CausingMalusesToWeaponUsage
{
    /**
     * @return int
     */
    public function getMalusToFightNumber(): int;

    /**
     * @return int
     */
    public function getMalusToAttackNumber(): int;

    /**
     * @return int
     */
    public function getMalusToCover(): int;

    /**
     * @return int
     */
    public function getMalusToBaseOfWounds(): int;
}