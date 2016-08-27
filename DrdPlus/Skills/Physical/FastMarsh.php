<?php
namespace DrdPlus\Skills\Physical;

use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Codes\PhysicalSkillCode;

/**
 * @ORM\Entity()
 */
class FastMarsh extends PhysicalSkill
{
    const FAST_MARSH = PhysicalSkillCode::FAST_MARSH;

    /**
     * @return string
     */
    public function getName()
    {
        return self::FAST_MARSH;
    }
}
