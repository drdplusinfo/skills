<?php
namespace DrdPlus\Person\Skills\Physical;

use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Codes\PhysicalSkillCode;

/**
 * @ORM\Entity()
 */
class Blacksmithing extends PersonPhysicalSkill
{
    const BLACKSMITHING = PhysicalSkillCode::BLACKSMITHING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::BLACKSMITHING;
    }
}
