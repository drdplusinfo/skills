<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\SkillCodes;

class Painting extends PersonCombinedSkill
{
    const PAINTING = SkillCodes::PAINTING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::PAINTING;
    }
}
