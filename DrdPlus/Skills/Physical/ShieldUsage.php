<?php
namespace DrdPlus\Skills\Physical;

use DrdPlus\Codes\Skills\PhysicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Tables\Tables;
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
     * @param Tables $tables
     * @param int $shieldRestriction as a negative number
     * @return int negative number or zero
     * @throws \Granam\Integer\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Integer\Tools\Exceptions\ValueLostOnCast
     * @throws \Granam\Integer\Tools\Exceptions\NegativeIntegerCanNotBePositive
     */
    public function getMalusToFightNumber(Tables $tables, $shieldRestriction)
    {
        /**
         * using shield as a weapon means using something without skill (zero skill ShieldAsAWeapon respectively)
         *, @see PPH page 86 right column top
         */
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return
            $this->getRestrictionWithShield($tables, $shieldRestriction)
            + $tables->getWeaponSkillTable()->getFightNumberMalusForSkillRank(0);
    }

    /**
     * Applicable to lower shield Restriction (Fight number malus), but can not make it positive.
     *
     * @param Tables $tables
     * @param int $shieldRestriction
     * @return int
     * @throws \Granam\Integer\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Integer\Tools\Exceptions\ValueLostOnCast
     * @throws \Granam\Integer\Tools\Exceptions\NegativeIntegerCanNotBePositive
     */
    public function getRestrictionWithShield(Tables $tables, $shieldRestriction)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        $malusFromRestriction = ToInteger::toNegativeInteger($shieldRestriction)
            + $tables->getShieldUsageSkillTable()->getRestrictionBonusForSkillRank($this->getCurrentSkillRank());
        if ($malusFromRestriction > 0) {
            return 0; // skill can lower the malus, but can not give bonus
        }

        return $malusFromRestriction;
    }

    /**
     * Only for shield as a weapon!
     *
     * @param Tables $tables
     * @return int
     */
    public function getMalusToAttackNumber(Tables $tables)
    {
        /**
         * using shield as a weapon means using something without skill (zero skill ShieldAsAWeapon respectively)
         *
         * @see PPH page 86 right column top
         */
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $tables->getWeaponSkillTable()->getAttackNumberMalusForSkillRank(0);
    }

    /**
     * @param Tables $tables
     * @return int
     */
    public function getMalusToCover(Tables $tables)
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $tables->getShieldUsageSkillTable()->getCoverMalusForSkillRank($this->getCurrentSkillRank());
    }

    /**
     * Only for shield as a weapon!
     *
     * @param Tables $tables
     * @return int
     */
    public function getMalusToBaseOfWounds(Tables $tables)
    {
        /**
         * using shield as a weapon means using something without skill (zero skill ShieldAsAWeapon respectively)
         *
         * @see PPH page 86 right column top
         */
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        return $tables->getWeaponSkillTable()->getBaseOfWoundsMalusForSkillRank(0);
    }
}