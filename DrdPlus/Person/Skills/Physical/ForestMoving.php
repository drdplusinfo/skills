<?php
namespace DrdPlus\Person\Skills\Physical;

use DrdPlus\Codes\PhysicalSkillCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class ForestMoving extends PersonPhysicalSkill
{
    const FOREST_MOVING = PhysicalSkillCode::FOREST_MOVING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::FOREST_MOVING;
    }
}
