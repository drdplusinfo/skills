<?php
namespace DrdPlus\Person\Skills\Physical;

use DrdPlus\Codes\PhysicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Tables\Armaments\Shields\MissingShieldSkillTable;
use DrdPlus\Tables\Armaments\Weapons\MissingWeaponSkillTable;
use Granam\Integer\Tools\ToInteger;

/**
 * @ORM\Entity()
 */
class ShieldUsage extends PersonPhysicalSkill
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
     * @param MissingShieldSkillTable $missingShieldSkillsTable
     * @param int $shieldRestriction as a negative number
     * @param MissingWeaponSkillTable $missingWeaponSkillsTable
     * @return int negative number or zero
     * @throws \Granam\Integer\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Integer\Tools\Exceptions\ValueLostOnCast
     * @throws \Granam\Integer\Tools\Exceptions\NegativeIntegerCanNotBePositive
     */
    public function getMalusToFightNumber(
        MissingShieldSkillTable $missingShieldSkillsTable,
        $shieldRestriction,
        MissingWeaponSkillTable $missingWeaponSkillsTable
    )
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return
            $this->getRestrictionWithShield($missingShieldSkillsTable, $shieldRestriction)
            /**
             * using shield as a weapon means using something without skill (zero skill ShieldAsAWeapon respectively)
             * @see PPH page 86 right column top
             */
            + $missingWeaponSkillsTable->getFightNumberForWeaponSkill(0);
    }

    /**
     * Applicable to lower shield Restriction (Fight number malus), but can not make it positive.
     * @param MissingShieldSkillTable $missingShieldSkillsTable
     * @param int $shieldRestriction
     * @return int
     * @throws \Granam\Integer\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Integer\Tools\Exceptions\ValueLostOnCast
     * @throws \Granam\Integer\Tools\Exceptions\NegativeIntegerCanNotBePositive
     */
    public function getRestrictionWithShield(MissingShieldSkillTable $missingShieldSkillsTable, $shieldRestriction)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $malusFromRestriction = ToInteger::toNegativeInteger($shieldRestriction)
            + $missingShieldSkillsTable->getRestrictionBonusForSkillRank($this->getCurrentSkillRank()->getValue());
        if ($malusFromRestriction > 0) {
            return 0; // skill can lower the malus, but can not give bonus
        }

        return $malusFromRestriction;
    }

    /**
     * Only for shield as a weapon!
     * @param MissingWeaponSkillTable $missingWeaponSkillsTable
     * @return int
     */
    public function getMalusToAttackNumber(MissingWeaponSkillTable $missingWeaponSkillsTable)
    {
        /**
         * using shield as a weapon means using something without skill (zero skill ShieldAsAWeapon respectively)
         * @see PPH page 86 right column top
         */
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $missingWeaponSkillsTable->getAttackNumberForWeaponSkill(0);
    }

    /**
     * @param MissingShieldSkillTable $missingShieldSkillsTable
     * @return int
     */
    public function getMalusToCover(MissingShieldSkillTable $missingShieldSkillsTable)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $missingShieldSkillsTable->getCoverForSkillRank($this->getCurrentSkillRank()->getValue());
    }

    /**
     * Only for shield as a weapon!
     * @param MissingWeaponSkillTable $missingWeaponSkillsTable
     * @return int
     */
    public function getMalusToBaseOfWounds(MissingWeaponSkillTable $missingWeaponSkillsTable)
    {
        /**
         * using shield as a weapon means using something without skill (zero skill ShieldAsAWeapon respectively)
         * @see PPH page 86 right column top
         */
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $missingWeaponSkillsTable->getBaseOfWoundsForWeaponSkill(0);
    }
}