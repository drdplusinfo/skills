<?php
namespace DrdPlus\Person\Skills\Physical;

use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Codes\SkillCodes;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Athletics extends PersonPhysicalSkill
{
    const ATHLETICS = SkillCodes::ATHLETICS;

    /**
     * @return string
     */
    public function getName()
    {
        return self::ATHLETICS;
    }

}
