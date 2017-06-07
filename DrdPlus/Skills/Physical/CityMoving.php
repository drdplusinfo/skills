<?php
namespace DrdPlus\Skills\Physical;

use DrdPlus\Codes\Skills\PhysicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Skills\WithBonusToMovementSpeed;

/**
 * @ORM\Entity()
 */
class CityMoving extends PhysicalSkill implements WithBonusToMovementSpeed
{
    const CITY_MOVING = PhysicalSkillCode::CITY_MOVING;

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