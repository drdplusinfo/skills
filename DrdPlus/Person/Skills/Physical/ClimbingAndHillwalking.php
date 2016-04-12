<?php
namespace DrdPlus\Person\Skills\Physical;

use DrdPlus\Codes\SkillCodes;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class ClimbingAndHillwalking extends PersonPhysicalSkill
{
    const CLIMBING_AND_HILLWALKING = SkillCodes::CLIMBING_AND_HILLWALKING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::CLIMBING_AND_HILLWALKING;
    }
}
