<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\SkillCodes;

class HandingWithAnimals extends PersonCombinedSkill
{
    const HANDING_WITH_ANIMALS = SkillCodes::HANDING_WITH_ANIMALS;

    /**
     * @return string
     */
    public function getName()
    {
        return self::HANDING_WITH_ANIMALS;
    }
}
