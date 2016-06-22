<?php
namespace DrdPlus\Person\Skills\Physical;

use DrdPlus\Codes\PhysicalSkillCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class MovingInMountains extends PersonPhysicalSkill
{
    const MOVING_IN_MOUNTAINS = PhysicalSkillCode::MOVING_IN_MOUNTAINS;

    /**
     * @return string
     */
    public function getName()
    {
        return self::MOVING_IN_MOUNTAINS;
    }
}
