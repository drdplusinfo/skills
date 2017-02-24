<?php
namespace DrdPlus\Skills\Physical;

use DrdPlus\Codes\Skills\PhysicalSkillCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Riding extends PhysicalSkill
{
    const RIDING = PhysicalSkillCode::RIDING;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::RIDING;
    }
}