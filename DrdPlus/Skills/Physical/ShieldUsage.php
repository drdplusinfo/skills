<?php
namespace DrdPlus\Skills\Physical;

use DrdPlus\Codes\Skills\PhysicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Tables\Armaments\Shields\ShieldUsageSkillTable;
use DrdPlus\Tables\Armaments\Weapons\WeaponSkillTable;
use Granam\Integer\Tools\ToInteger;

/**
 * @ORM\Entity()
 */
class ShieldUsage extends PhysicalSkill
{
    const SHIELD_USAGE = PhysicalSkillCode::SHIELD_USAGE;

    /**
     * @return string
     */
    public function getName()
    {
        return self::SHIELD_USAGE;
    }

    /**
     * Only for using shield as a weapon!
     *
     * @param ShieldUsageSkillTable $missingShieldSkillsTable
     * @param int $shieldRestriction as a negative number
     * @param WeaponSkillTable $missingWeaponSkillsTable
     * @return int negative number or zero
     * @throws \Granam\Integer\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Integer\Tools\Exceptions\ValueLostOnCast
     * @throws \Granam\Integer\Tools\Exceptions\NegativeIntegerCanNotBePositive
     */
    public function getMalusToFightNumber(
        ShieldUsageSkillTable $missingShieldSkillsTable,
        $shieldRestriction,
        WeaponSkillTable $missingWeaponSkillsTable
    )
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return
            $this->getRestrictionWithShield($missingShieldSkillsTable, $shieldRestriction)
            /**
             * using shield as a weapon means using something without skill (zero skill ShieldAsAWeapon respectively)
             *
             * @see PPH page 86 right column top
             */
            + $missingWeaponSkillsTable->getFightNumberMalusForSkill(0);
    }

    /**
     * Applicable to lower shield Restriction (Fight number malus), but can not make it positive.
     *
     * @param ShieldUsageSkillTable $missingShieldSkillsTable
     * @param int $shieldRestriction
     * @return int
     * @throws \Granam\Integer\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Integer\Tools\Exceptions\ValueLostOnCast
     * @throws \Granam\Integer\Tools\Exceptions\NegativeIntegerCanNotBePositive
     */
    public function getRestrictionWithShield(ShieldUsageSkillTable $missingShieldSkillsTable, $shieldRestriction)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $malusFromRestriction = ToInteger::toNegativeInteger($shieldRestriction)
            + $missingShieldSkillsTable->getRestrictionBonusForSkill($this->getCurrentSkillRank()->getValue());
        if ($malusFromRestriction > 0) {
            return 0; // skill can lower the malus, but can not give bonus
        }

        return $malusFromRestriction;
    }

    /**
     * Only for shield as a weapon!
     *
     * @param WeaponSkillTable $missingWeaponSkillsTable
     * @return int
     */
    public function getMalusToAttackNumber(WeaponSkillTable $missingWeaponSkillsTable)
    {
        /**
         * using shield as a weapon means using something without skill (zero skill ShieldAsAWeapon respectively)
         *
         * @see PPH page 86 right column top
         */
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $missingWeaponSkillsTable->getAttackNumberMalusForSkill(0);
    }

    /**
     * @param ShieldUsageSkillTable $missingShieldSkillsTable
     * @return int
     */
    public function getMalusToCover(ShieldUsageSkillTable $missingShieldSkillsTable)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $missingShieldSkillsTable->getCoverForSkillRank($this->getCurrentSkillRank()->getValue());
    }

    /**
     * Only for shield as a weapon!
     *
     * @param WeaponSkillTable $missingWeaponSkillsTable
     * @return int
     */
    public function getMalusToBaseOfWounds(WeaponSkillTable $missingWeaponSkillsTable)
    {
        /**
         * using shield as a weapon means using something without skill (zero skill ShieldAsAWeapon respectively)
         *
         * @see PPH page 86 right column top
         */
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $missingWeaponSkillsTable->getBaseOfWoundsMalusForSkill(0);
    }
}