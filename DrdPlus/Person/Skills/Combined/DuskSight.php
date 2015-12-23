<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\SkillCodes;

class DuskSight extends PersonCombinedSkill
{
    const DUSK_SIGHT = SkillCodes::DUSK_SIGHT;

    /**
     * @return string
     */
    public function getName()
    {
        return self::DUSK_SIGHT;
    }
}
