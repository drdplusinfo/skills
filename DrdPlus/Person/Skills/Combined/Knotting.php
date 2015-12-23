<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\SkillCodes;

class Knotting extends PersonCombinedSkill
{
    const KNOTTING = SkillCodes::KNOTTING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::KNOTTING;
    }
}
