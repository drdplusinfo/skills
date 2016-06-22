<?php
namespace DrdPlus\Person\Skills\Physical;

use DrdPlus\Codes\PhysicalSkillCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class ClimbingAndHillwalking extends PersonPhysicalSkill
{
    const CLIMBING_AND_HILLWALKING = PhysicalSkillCode::CLIMBING_AND_HILLWALKING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::CLIMBING_AND_HILLWALKING;
    }
}
