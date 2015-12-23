<?php
namespace DrdPlus\Person\Skills\Physical;

use DrdPlus\Codes\SkillCodes;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class ForestMoving extends PersonPhysicalSkill
{
    const FOREST_MOVING = SkillCodes::FOREST_MOVING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::FOREST_MOVING;
    }
}
