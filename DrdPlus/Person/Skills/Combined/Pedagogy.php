<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\SkillCodes;

class Pedagogy extends PersonCombinedSkill
{
    const PEDAGOGY = SkillCodes::PEDAGOGY;

    /**
     * @return string
     */
    public function getName()
    {
        return self::PEDAGOGY;
    }

}
