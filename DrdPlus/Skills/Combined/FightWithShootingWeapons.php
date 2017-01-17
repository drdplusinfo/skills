<?php
namespace DrdPlus\Skills\Combined;

use DrdPlus\Skills\CausingMalusesToWeaponUsage;
use DrdPlus\Tables\Armaments\Weapons\WeaponSkillTable;

abstract class FightWithShootingWeapons extends CombinedSkill implements CausingMalusesToWeaponUsage
{

    /**
     * @param WeaponSkillTable $missingWeaponSkillsTable
     * @return int
     */
    public function getMalusToFightNumber(WeaponSkillTable $missingWeaponSkillsTable)
    {
        return $missingWeaponSkillsTable->getFightNumberForWeaponSkill($this->getCurrentSkillRank()->getValue());
    }

    /**
     * @param WeaponSkillTable $missingWeaponSkillsTable
     * @return int
     */
    public function getMalusToAttackNumber(WeaponSkillTable $missingWeaponSkillsTable)
    {
        return $missingWeaponSkillsTable->getAttackNumberForWeaponSkill($this->getCurrentSkillRank()->getValue());
    }

    /**
     * @param WeaponSkillTable $missingWeaponSkillsTable
     * @return int
     */
    public function getMalusToCover(WeaponSkillTable $missingWeaponSkillsTable)
    {
        return $missingWeaponSkillsTable->getCoverForWeaponSkill($this->getCurrentSkillRank()->getValue());
    }

    /**
     * @param WeaponSkillTable $missingWeaponSkillsTable
     * @return int
     */
    public function getMalusToBaseOfWounds(WeaponSkillTable $missingWeaponSkillsTable)
    {
        return $missingWeaponSkillsTable->getBaseOfWoundsForWeaponSkill($this->getCurrentSkillRank()->getValue());
    }
}