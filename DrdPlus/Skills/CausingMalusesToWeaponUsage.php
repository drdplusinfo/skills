<?php
namespace DrdPlus\Skills;

use DrdPlus\Tables\Tables;

interface CausingMalusesToWeaponUsage
{
    /**
     * @param Tables $tables
     * @return int
     */
    public function getMalusToFightNumber(Tables $tables);

    /**
     * @param Tables $tables
     * @return int
     */
    public function getMalusToAttackNumber(Tables $tables);

    /**
     * @param Tables $tables
     * @return int
     */
    public function getMalusToCover(Tables $tables);

    /**
     * @param Tables $tables
     * @return int
     */
    public function getMalusToBaseOfWounds(Tables $tables);
}