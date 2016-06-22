<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\CombinedSkillCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class HandlingWithAnimals extends PersonCombinedSkill
{
    const HANDLING_WITH_ANIMALS = CombinedSkillCode::HANDLING_WITH_ANIMALS;

    /**
     * @return string
     */
    public function getName()
    {
        return self::HANDLING_WITH_ANIMALS;
    }
}
