<?php
namespace DrdPlus\Person\Skills\Physical;

use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Codes\SkillCodes;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Blacksmithing extends PersonPhysicalSkill
{
    const BLACKSMITHING = SkillCodes::BLACKSMITHING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::BLACKSMITHING;
    }
}
