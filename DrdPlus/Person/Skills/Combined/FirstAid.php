<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\SkillCodes;

class FirstAid extends PersonCombinedSkill
{
    const FIRST_AID = SkillCodes::FIRST_AID;

    /**
     * @return string
     */
    public function getName()
    {
        return self::FIRST_AID;
    }
}
