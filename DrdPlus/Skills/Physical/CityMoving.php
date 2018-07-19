<?php
declare(strict_types = 1);

namespace DrdPlus\Skills\Physical;

use DrdPlus\Codes\Skills\PhysicalSkillCode;
use DrdPlus\Skills\WithBonusToMovementSpeed;

/**
 * @link https://pph.drdplus.info/#pohyb_ve_meste
 * @Doctrine\ORM\Mapping\Entity()
 */
class CityMoving extends PhysicalSkill implements WithBonusToMovementSpeed
{
    public const CITY_MOVING = PhysicalSkillCode::CITY_MOVING;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::CITY_MOVING;
    }

    /**
     * @return int
     */
    public function getBonusToMovementSpeed(): int
    {
        return $this->getCurrentSkillRank()->getValue();
    }

    /**
     * @return int
     */
    public function getBonusToIntelligenceOrSenses(): int
    {
        return 2 * $this->getCurrentSkillRank()->getValue();
    }
}