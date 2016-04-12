<?php
namespace DrdPlus\Person\Skills\Physical;

use DrdPlus\Codes\SkillCodes;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class MovingInMountains extends PersonPhysicalSkill
{
    const MOVING_IN_MOUNTAINS = SkillCodes::MOVING_IN_MOUNTAINS;

    /**
     * @return string
     */
    public function getName()
    {
        return self::MOVING_IN_MOUNTAINS;
    }
}
