<?php
namespace DrdPlus\Skills\Physical;

use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Codes\PhysicalSkillCode;

/**
 * @ORM\Entity()
 */
class Athletics extends PhysicalSkill
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
