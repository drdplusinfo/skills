<?php
namespace DrdPlus\Person\Skills\Physical;

use DrdPlus\Codes\SkillCodes;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Flying extends PersonPhysicalSkill
{
    const FLYING = SkillCodes::FLYING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::FLYING;
    }
}
