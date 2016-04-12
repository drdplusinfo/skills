<?php
namespace DrdPlus\Person\Skills\Physical;

use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Codes\SkillCodes;

/**
 * @ORM\Entity()
 */
class FastMarsh extends PersonPhysicalSkill
{
    const FAST_MARSH = SkillCodes::FAST_MARSH;

    /**
     * @return string
     */
    public function getName()
    {
        return self::FAST_MARSH;
    }
}
