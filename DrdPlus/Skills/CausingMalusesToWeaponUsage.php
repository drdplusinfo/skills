<?php
namespace DrdPlus\Skills;

use DrdPlus\Tables\Armaments\Weapons\WeaponSkillTable;

interface CausingMalusesToWeaponUsage
{
    /**
     * @param WeaponSkillTable $missingWeaponSkillsTable
     * @return int
     */
    public function getMalusToFightNumber(WeaponSkillTable $missingWeaponSkillsTable);

    /**
     * @param WeaponSkillTable $missingWeaponSkillsTable
     * @return int
     */
    public function getMalusToAttackNumber(WeaponSkillTable $missingWeaponSkillsTable);

    /**
     * @param WeaponSkillTable $missingWeaponSkillsTable
     * @return int
     */
    public function getMalusToCover(WeaponSkillTable $missingWeaponSkillsTable);

    /**
     * @param WeaponSkillTable $missingWeaponSkillsTable
     * @return int
     */
    public function getMalusToBaseOfWounds(WeaponSkillTable $missingWeaponSkillsTable);
}