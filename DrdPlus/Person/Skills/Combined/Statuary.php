<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\SkillCodes;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Statuary extends PersonCombinedSkill
{
    const STATUARY = SkillCodes::STATUARY;

    /**
     * @return string
     */
    public function getName()
    {
        return self::STATUARY;
    }
}
