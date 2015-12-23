<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\SkillCodes;

class Seduction extends PersonCombinedSkill
{
    const SEDUCTION = SkillCodes::SEDUCTION;

    /**
     * @return string
     */
    public function getName()
    {
        return self::SEDUCTION;
    }
}
