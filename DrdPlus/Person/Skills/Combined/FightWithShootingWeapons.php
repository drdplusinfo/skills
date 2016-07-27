<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Person\Skills\CausingMalusesToWeaponUsage;
use DrdPlus\Tables\Armaments\Weapons\MissingWeaponSkillsTable;

abstract class FightWithShootingWeapons extends PersonCombinedSkill implements CausingMalusesToWeaponUsage
{

    /**
     * @param MissingWeaponSkillsTable $missingWeaponSkillsTable
     * @return int
     */
    public function getMalusToFightNumber(MissingWeaponSkillsTable $missingWeaponSkillsTable)
    {
        return $missingWeaponSkillsTable->getFightNumberForWeaponSkill($this->determineCurrentSkillRank());
    }

    /**
     * @return int
     */
    private function determineCurrentSkillRank()
    {
        return $this->getCurrentSkillRank()
            ? $this->getCurrentSkillRank()->getValue()
            : 0;
    }

    /**
     * @param MissingWeaponSkillsTable $missingWeaponSkillsTable
     * @return int
     */
    public function getMalusToAttackNumber(MissingWeaponSkillsTable $missingWeaponSkillsTable)
    {
        return $missingWeaponSkillsTable->getAttackNumberForWeaponSkill($this->determineCurrentSkillRank());
    }

    /**
     * @param MissingWeaponSkillsTable $missingWeaponSkillsTable
     * @return int
     */
    public function getMalusToCover(MissingWeaponSkillsTable $missingWeaponSkillsTable)
    {
        return $missingWeaponSkillsTable->getCoverForWeaponSkill($this->determineCurrentSkillRank());
    }

    /**
     * @param MissingWeaponSkillsTable $missingWeaponSkillsTable
     * @return int
     */
    public function getMalusToBaseOfWounds(MissingWeaponSkillsTable $missingWeaponSkillsTable)
    {
        return $missingWeaponSkillsTable->getBaseOfWoundsForWeaponSkill($this->determineCurrentSkillRank());
    }
}