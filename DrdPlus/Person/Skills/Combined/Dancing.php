<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\SkillCodes;

class Dancing extends PersonCombinedSkill
{
    const DANCING = SkillCodes::DANCING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::DANCING;
    }
}
