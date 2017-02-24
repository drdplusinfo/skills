<?php
namespace DrdPlus\Skills\Physical;

use DrdPlus\Codes\Skills\PhysicalSkillCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class MovingInMountains extends PhysicalSkill
{
    const MOVING_IN_MOUNTAINS = PhysicalSkillCode::MOVING_IN_MOUNTAINS;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::MOVING_IN_MOUNTAINS;
    }
}