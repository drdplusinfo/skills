<?php
namespace DrdPlus\Person\Skills\Physical;

use DrdPlus\Codes\SkillCodes;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class CityMoving extends PersonPhysicalSkill
{
    const CITY_MOVING = SkillCodes::CITY_MOVING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::CITY_MOVING;
    }
}
