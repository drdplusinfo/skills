<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\SkillCodes;

class Singing extends PersonCombinedSkill
{
    const SINGING = SkillCodes::SINGING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::SINGING;
    }
}
