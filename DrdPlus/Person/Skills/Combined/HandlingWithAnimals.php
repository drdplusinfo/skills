<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\SkillCodes;

class HandlingWithAnimals extends PersonCombinedSkill
{
    const HANDLING_WITH_ANIMALS = SkillCodes::HANDLING_WITH_ANIMALS;

    /**
     * @return string
     */
    public function getName()
    {
        return self::HANDLING_WITH_ANIMALS;
    }
}
