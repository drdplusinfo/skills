<?php
namespace DrdPlus\Person\Skills\Physical;

use DrdPlus\Codes\PhysicalSkillCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Flying extends PersonPhysicalSkill
{
    const FLYING = PhysicalSkillCode::FLYING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::FLYING;
    }
}
