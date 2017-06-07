<?php
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