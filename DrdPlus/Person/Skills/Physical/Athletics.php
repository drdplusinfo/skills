<?php
namespace DrdPlus\Person\Skills\Physical;

use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Codes\PhysicalSkillCode;

/**
 * @ORM\Entity()
 */
class Athletics extends PersonPhysicalSkill
{
    const ATHLETICS = PhysicalSkillCode::ATHLETICS;

    /**
     * @return string
     */
    public function getName()
    {
        return self::ATHLETICS;
    }

}
