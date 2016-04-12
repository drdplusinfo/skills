<?php
namespace DrdPlus\Person\Skills\Physical;

use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Codes\SkillCodes;

/**
 * @ORM\Entity()
 */
class ArmorWearing extends PersonPhysicalSkill
{
    const ARMOR_WEARING = SkillCodes::ARMOR_WEARING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::ARMOR_WEARING;
    }
}
