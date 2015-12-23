<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\SkillCodes;

class Handwork extends PersonCombinedSkill
{
    const HANDWORK = SkillCodes::HANDWORK;

    /**
     * @return string
     */
    public function getName()
    {
        return self::HANDWORK;
    }

}
