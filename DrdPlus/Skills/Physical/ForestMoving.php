<?php
namespace DrdPlus\Skills\Physical;

use DrdPlus\Codes\Skills\PhysicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Skills\WithBonus;

/**
 * @ORM\Entity()
 */
class ForestMoving extends PhysicalSkill implements WithBonus
{
    const FOREST_MOVING = PhysicalSkillCode::FOREST_MOVING;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::FOREST_MOVING;
    }

    /**
     * @return int
     */
    public function getBonus(): int
    {
        return $this->getCurrentSkillRank()->getValue();
    }

}