<?php
namespace DrdPlus\Person\Skills;

use DrdPlus\Tables\Armaments\Weapons\MissingWeaponSkillTable;

interface CausingMalusesToWeaponUsage
{
    /**
     * @param MissingWeaponSkillTable $missingWeaponSkillsTable
     * @return int
     */
    public function getMalusToFightNumber(MissingWeaponSkillTable $missingWeaponSkillsTable);

    /**
     * @param MissingWeaponSkillTable $missingWeaponSkillsTable
     * @return int
     */
    public function getMalusToAttackNumber(MissingWeaponSkillTable $missingWeaponSkillsTable);

    /**
     * @param MissingWeaponSkillTable $missingWeaponSkillsTable
     * @return int
     */
    public function getMalusToCover(MissingWeaponSkillTable $missingWeaponSkillsTable);

    /**
     * @param MissingWeaponSkillTable $missingWeaponSkillsTable
     * @return int
     */
    public function getMalusToBaseOfWounds(MissingWeaponSkillTable $missingWeaponSkillsTable);
}