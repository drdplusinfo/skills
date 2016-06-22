<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\CombinedSkillCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Statuary extends PersonCombinedSkill
{
    const STATUARY = CombinedSkillCode::STATUARY;

    /**
     * @return string
     */
    public function getName()
    {
        return self::STATUARY;
    }
}
