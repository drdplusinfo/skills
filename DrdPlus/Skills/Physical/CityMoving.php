<?php
namespace DrdPlus\Skills\Physical;

use DrdPlus\Codes\Skills\PhysicalSkillCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class CityMoving extends PhysicalSkill
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
    public function getBonusToSpeed(): int
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