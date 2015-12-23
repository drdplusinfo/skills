<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\SkillCodes;

class Herbalism extends PersonCombinedSkill
{
    const HERBALISM = SkillCodes::HERBALISM;

    /**
     * @return string
     */
    public function getName()
    {
        return self::HERBALISM;
    }
}
