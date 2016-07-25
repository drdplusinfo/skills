<?php
namespace DrdPlus\Person\Skills\Physical;

use DrdPlus\Person\Skills\CausingMalusesToWeaponUsage;
use DrdPlus\Tables\Armaments\Weapons\MissingWeaponSkillsTable;

/**
 * For maluses see PPH page 93 left column
 */
abstract class FightWithMeleeWeapon extends PersonPhysicalSkill implements CausingMalusesToWeaponUsage
{
    /**
     * @param MissingWeaponSkillsTable $missingWeaponSkillsTable
     * @return int
     */
    public function getMalusToFightNumber(MissingWeaponSkillsTable $missingWeaponSkillsTable)
    {
        return $missingWeaponSkillsTable->getFightNumberForWeaponSkill($this->getCurrentSkillRank()->getValue());
    }

    /**
     * @param MissingWeaponSkillsTable $missingWeaponSkillsTable
     * @return int
     */
    public function getMalusToAttackNumber(MissingWeaponSkillsTable $missingWeaponSkillsTable)
    {
        return $missingWeaponSkillsTable->getAttackNumberForWeaponSkill($this->getCurrentSkillRank()->getValue());
    }

    /**
     * @param MissingWeaponSkillsTable $missingWeaponSkillsTable
     * @return int
     */
    public function getMalusToCover(MissingWeaponSkillsTable $missingWeaponSkillsTable)
    {
        return $missingWeaponSkillsTable->getCoverForWeaponSkill($this->getCurrentSkillRank()->getValue());
    }

    /**
     * @param MissingWeaponSkillsTable $missingWeaponSkillsTable
     * @return int
     */
    public function getMalusToBaseOfWounds(MissingWeaponSkillsTable $missingWeaponSkillsTable)
    {
        return $missingWeaponSkillsTable->getBaseOfWoundsForWeaponSkill($this->getCurrentSkillRank()->getValue());
    }
}
