<?php
namespace DrdPlus\Person\Skills\Physical;

use DrdPlus\Codes\SkillCodes;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Riding extends PersonPhysicalSkill
{
    const RIDING = SkillCodes::RIDING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::RIDING;
    }
}
