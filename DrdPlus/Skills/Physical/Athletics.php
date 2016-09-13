<?php
namespace DrdPlus\Skills\Physical;

use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Codes\Skills\PhysicalSkillCode;

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