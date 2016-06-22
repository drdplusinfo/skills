<?php
namespace DrdPlus\Person\Skills\Physical;

use DrdPlus\Codes\PhysicalSkillCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class BoatDriving extends PersonPhysicalSkill
{
    const BOAT_DRIVING = PhysicalSkillCode::BOAT_DRIVING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::BOAT_DRIVING;
    }
}
