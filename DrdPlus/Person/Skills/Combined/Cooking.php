<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\SkillCodes;

class Cooking extends PersonCombinedSkill
{
    const COOKING = SkillCodes::COOKING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::COOKING;
    }

}
