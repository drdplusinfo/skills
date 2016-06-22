<?php
namespace DrdPlus\Person\Skills\Physical;

use DrdPlus\Codes\PhysicalSkillCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Riding extends PersonPhysicalSkill
{
    const RIDING = PhysicalSkillCode::RIDING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::RIDING;
    }
}
