<?php
declare(strict_types = 1);

namespace DrdPlus\Skills\Physical;

use DrdPlus\Codes\Skills\PhysicalSkillCode;
use DrdPlus\Codes\Transport\MovementTypeCode;
use DrdPlus\Skills\WithBonus;

/**
 * @link https://pph.drdplus.info/#jezdectvi
 * @Doctrine\ORM\Mapping\Entity()
 */
class Riding extends PhysicalSkill implements WithBonus
{
    public const RIDING = PhysicalSkillCode::RIDING;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::RIDING;
    }

    /**
     * @return int
     */
    public function getBonus(): int
    {
        $currentSkillRank = $this->getCurrentSkillRank()->getValue();

        if ($currentSkillRank === 0) {
            return 0;
        }

        return $currentSkillRank * 2 + 2;
    }

    /**
     * @return int
     */
    public function getMalusToFightAttackAndDefenseNumber(): int
    {
        return 2 * $this->getCurrentSkillRank()->getValue() - 6;
    }

    /**
     * @return MovementTypeCode
     */
    public function getGaitWearyLike(): MovementTypeCode
    {
        if ($this->getCurrentSkillRank()->getValue() === 0) {
            return MovementTypeCode::getIt(MovementTypeCode::WALK);
        }

        return MovementTypeCode::getIt(MovementTypeCode::WAITING); // no fatigue at all
    }

    /**
     * @return MovementTypeCode
     */
    public function getTrotWearyLike(): MovementTypeCode
    {
        if ($this->getCurrentSkillRank()->getValue() === 0) {
            return MovementTypeCode::getIt(MovementTypeCode::RUSH);
        }
        if ($this->getCurrentSkillRank()->getValue() === 1) {
            return MovementTypeCode::getIt(MovementTypeCode::WALK);
        }

        return MovementTypeCode::getIt(MovementTypeCode::WAITING); // no fatigue at all
    }

    /**
     * @return MovementTypeCode
     */
    public function getCanterWearyLike(): MovementTypeCode
    {
        if ($this->getCurrentSkillRank()->getValue() === 0) {
            return MovementTypeCode::getIt(MovementTypeCode::RUN);
        }
        if ($this->getCurrentSkillRank()->getValue() === 1) {
            return MovementTypeCode::getIt(MovementTypeCode::RUSH);
        }
        if ($this->getCurrentSkillRank()->getValue() === 2) {
            return MovementTypeCode::getIt(MovementTypeCode::WALK);
        }

        return MovementTypeCode::getIt(MovementTypeCode::WAITING); // no fatigue at all
    }

    /**
     * @return MovementTypeCode
     */
    public function getGallopWearyLike(): MovementTypeCode
    {
        if ($this->getCurrentSkillRank()->getValue() === 0) {
            return MovementTypeCode::getIt(MovementTypeCode::SPRINT);
        }
        if ($this->getCurrentSkillRank()->getValue() === 1) {
            return MovementTypeCode::getIt(MovementTypeCode::RUN);
        }
        if ($this->getCurrentSkillRank()->getValue() === 2) {
            return MovementTypeCode::getIt(MovementTypeCode::RUSH);
        }

        return MovementTypeCode::getIt(MovementTypeCode::WAITING); // no fatigue at all
    }

}