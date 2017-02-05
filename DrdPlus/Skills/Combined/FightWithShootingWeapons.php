<?php
namespace DrdPlus\Skills\Combined;

use DrdPlus\Skills\CausingMalusesToWeaponUsage;
use DrdPlus\Tables\Tables;

abstract class FightWithShootingWeapons extends CombinedSkill implements CausingMalusesToWeaponUsage
{

    /**
     * @param Tables $tables
     * @return int
     */
    public function getMalusToFightNumber(Tables $tables)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $tables->getMissingWeaponSkillTable()->getFightNumberMalusForSkillRank($this->getCurrentSkillRank());
    }

    /**
     * @param Tables $tables
     * @return int
     */
    public function getMalusToAttackNumber(Tables $tables)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $tables->getMissingWeaponSkillTable()->getAttackNumberMalusForSkillRank($this->getCurrentSkillRank());
    }

    /**
     * @param Tables $tables
     * @return int
     */
    public function getMalusToCover(Tables $tables)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $tables->getMissingWeaponSkillTable()->getCoverMalusForSkillRank($this->getCurrentSkillRank());
    }

    /**
     * @param Tables $tables
     * @return int
     */
    public function getMalusToBaseOfWounds(Tables $tables)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $tables->getMissingWeaponSkillTable()->getBaseOfWoundsMalusForSkillRank($this->getCurrentSkillRank());
    }
}