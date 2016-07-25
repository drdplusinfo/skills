<?php
namespace DrdPlus\Person\Skills;

use DrdPlus\Tables\Armaments\Weapons\MissingWeaponSkillsTable;

interface CausingMalusesToWeaponUsage
{
    /**
     * @param MissingWeaponSkillsTable $missingWeaponSkillsTable
     * @return int
     */
    public function getMalusToFightNumber(MissingWeaponSkillsTable $missingWeaponSkillsTable);

    /**
     * @param MissingWeaponSkillsTable $missingWeaponSkillsTable
     * @return int
     */
    public function getMalusToAttackNumber(MissingWeaponSkillsTable $missingWeaponSkillsTable);

    /**
     * @param MissingWeaponSkillsTable $missingWeaponSkillsTable
     * @return int
     */
    public function getMalusToCover(MissingWeaponSkillsTable $missingWeaponSkillsTable);

    /**
     * @param MissingWeaponSkillsTable $missingWeaponSkillsTable
     * @return int
     */
    public function getMalusToBaseOfWounds(MissingWeaponSkillsTable $missingWeaponSkillsTable);
}