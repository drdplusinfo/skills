<?php
namespace DrdPlus\Person\Skills\Physical;

use DrdPlus\Codes\SkillCodes;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class BoatDriving extends PersonPhysicalSkill
{
    const BOAT_DRIVING = SkillCodes::BOAT_DRIVING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::BOAT_DRIVING;
    }
}
