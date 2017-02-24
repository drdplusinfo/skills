<?php
namespace DrdPlus\Skills\Physical;

use DrdPlus\Codes\Skills\PhysicalSkillCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class ForestMoving extends PhysicalSkill
{
    const FOREST_MOVING = PhysicalSkillCode::FOREST_MOVING;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::FOREST_MOVING;
    }
}