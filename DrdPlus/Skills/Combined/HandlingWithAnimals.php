<?php
namespace DrdPlus\Skills\Combined;

use DrdPlus\Codes\Skills\CombinedSkillCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class HandlingWithAnimals extends CombinedSkill
{
    const HANDLING_WITH_ANIMALS = CombinedSkillCode::HANDLING_WITH_ANIMALS;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::HANDLING_WITH_ANIMALS;
    }
}