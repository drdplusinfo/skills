<?php
namespace DrdPlus\Skills\Physical;

use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Codes\Skills\PhysicalSkillCode;

/**
 * @ORM\Entity()
 */
class ArmorWearing extends PhysicalSkill
{
    const ARMOR_WEARING = PhysicalSkillCode::ARMOR_WEARING;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::ARMOR_WEARING;
    }
}