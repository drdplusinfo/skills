<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Person\Skills\CausingMalusesToWeaponUsage;
use DrdPlus\Tables\Armaments\Weapons\MissingWeaponSkillTable;

abstract class FightWithShootingWeapons extends PersonCombinedSkill implements CausingMalusesToWeaponUsage
{

    /**
     * @param MissingWeaponSkillTable $missingWeaponSkillsTable
     * @return int
     */
    public function getMalusToFightNumber(MissingWeaponSkillTable $missingWeaponSkillsTable)
    {
        return $missingWeaponSkillsTable->getFightNumberForWeaponSkill($this->getCurrentSkillRank()->getValue());
    }

    /**
     * @param MissingWeaponSkillTable $missingWeaponSkillsTable
     * @return int
     */
    public function getMalusToAttackNumber(MissingWeaponSkillTable $missingWeaponSkillsTable)
    {
        return $missingWeaponSkillsTable->getAttackNumberForWeaponSkill($this->getCurrentSkillRank()->getValue());
    }

    /**
     * @param MissingWeaponSkillTable $missingWeaponSkillsTable
     * @return int
     */
    public function getMalusToCover(MissingWeaponSkillTable $missingWeaponSkillsTable)
    {
        return $missingWeaponSkillsTable->getCoverForWeaponSkill($this->getCurrentSkillRank()->getValue());
    }

    /**
     * @param MissingWeaponSkillTable $missingWeaponSkillsTable
     * @return int
     */
    public function getMalusToBaseOfWounds(MissingWeaponSkillTable $missingWeaponSkillsTable)
    {
        return $missingWeaponSkillsTable->getBaseOfWoundsForWeaponSkill($this->getCurrentSkillRank()->getValue());
    }
}