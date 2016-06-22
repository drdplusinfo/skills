<?php
namespace DrdPlus\Person\Skills\Physical;

use DrdPlus\Codes\PhysicalSkillCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class CityMoving extends PersonPhysicalSkill
{
    const CITY_MOVING = PhysicalSkillCode::CITY_MOVING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::CITY_MOVING;
    }
}
