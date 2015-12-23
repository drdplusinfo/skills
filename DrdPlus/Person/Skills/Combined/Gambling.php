<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\SkillCodes;

class Gambling extends PersonCombinedSkill
{
    const GAMBLING = SkillCodes::GAMBLING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::GAMBLING;
    }
}
