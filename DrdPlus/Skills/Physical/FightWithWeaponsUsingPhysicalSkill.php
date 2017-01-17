<?php
namespace DrdPlus\Skills\Physical;

use DrdPlus\Skills\CausingMalusesToWeaponUsage;
use DrdPlus\Tables\Armaments\Weapons\WeaponSkillTable;

/**
 * For maluses see PPH page 93 left column
 */
abstract class FightWithWeaponsUsingPhysicalSkill extends PhysicalSkill implements CausingMalusesToWeaponUsage
{
    /**
     * @param WeaponSkillTable $missingWeaponSkillsTable
     * @return int
     */
    public function getMalusToFightNumber(WeaponSkillTable $missingWeaponSkillsTable)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $missingWeaponSkillsTable->getFightNumberForWeaponSkill($this->getCurrentSkillRank()->getValue());
    }

    /**
     * @param WeaponSkillTable $missingWeaponSkillsTable
     * @return int
     */
    public function getMalusToAttackNumber(WeaponSkillTable $missingWeaponSkillsTable)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $missingWeaponSkillsTable->getAttackNumberForWeaponSkill($this->getCurrentSkillRank()->getValue());
    }

    /**
     * @param WeaponSkillTable $missingWeaponSkillsTable
     * @return int
     */
    public function getMalusToCover(WeaponSkillTable $missingWeaponSkillsTable)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $missingWeaponSkillsTable->getCoverForWeaponSkill($this->getCurrentSkillRank()->getValue());
    }

    /**
     * @param WeaponSkillTable $missingWeaponSkillsTable
     * @return int
     */
    public function getMalusToBaseOfWounds(WeaponSkillTable $missingWeaponSkillsTable)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $missingWeaponSkillsTable->getBaseOfWoundsForWeaponSkill($this->getCurrentSkillRank()->getValue());
    }
}