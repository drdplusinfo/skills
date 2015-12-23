<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\SkillCodes;

class Showmanship extends PersonCombinedSkill
{
    const SHOWMANSHIP = SkillCodes::SHOWMANSHIP;

    /**
     * @return string
     */
    public function getName()
    {
        return self::SHOWMANSHIP;
    }
}
