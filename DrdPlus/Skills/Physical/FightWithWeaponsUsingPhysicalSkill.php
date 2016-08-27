<?php
namespace DrdPlus\Skills\Physical;

use DrdPlus\Skills\CausingMalusesToWeaponUsage;
use DrdPlus\Tables\Armaments\Weapons\MissingWeaponSkillTable;

/**
 * For maluses see PPH page 93 left column
 */
abstract class FightWithWeaponsUsingPhysicalSkill extends PhysicalSkill implements CausingMalusesToWeaponUsage
{
    /**
     * @param MissingWeaponSkillTable $missingWeaponSkillsTable
     * @return int
     */
    public function getMalusToFightNumber(MissingWeaponSkillTable $missingWeaponSkillsTable)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $missingWeaponSkillsTable->getFightNumberForWeaponSkill($this->getCurrentSkillRank()->getValue());
    }

    /**
     * @param MissingWeaponSkillTable $missingWeaponSkillsTable
     * @return int
     */
    public function getMalusToAttackNumber(MissingWeaponSkillTable $missingWeaponSkillsTable)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $missingWeaponSkillsTable->getAttackNumberForWeaponSkill($this->getCurrentSkillRank()->getValue());
    }

    /**
     * @param MissingWeaponSkillTable $missingWeaponSkillsTable
     * @return int
     */
    public function getMalusToCover(MissingWeaponSkillTable $missingWeaponSkillsTable)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $missingWeaponSkillsTable->getCoverForWeaponSkill($this->getCurrentSkillRank()->getValue());
    }

    /**
     * @param MissingWeaponSkillTable $missingWeaponSkillsTable
     * @return int
     */
    public function getMalusToBaseOfWounds(MissingWeaponSkillTable $missingWeaponSkillsTable)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $missingWeaponSkillsTable->getBaseOfWoundsForWeaponSkill($this->getCurrentSkillRank()->getValue());
    }
}