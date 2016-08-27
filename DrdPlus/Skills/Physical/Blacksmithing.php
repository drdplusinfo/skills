<?php
namespace DrdPlus\Skills\Physical;

use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Codes\PhysicalSkillCode;

/**
 * @ORM\Entity()
 */
class Blacksmithing extends PhysicalSkill
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
