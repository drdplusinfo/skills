<?php
namespace DrdPlus\Skills\Combined;

use DrdPlus\Codes\Skills\CombinedSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Skills\WithBonus;

/**
 * @ORM\Entity()
 */
class HandlingWithAnimals extends CombinedSkill implements WithBonus
{
    const HANDLING_WITH_ANIMALS = CombinedSkillCode::HANDLING_WITH_ANIMALS;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::HANDLING_WITH_ANIMALS;
    }

    /**
     * @return int
     */
    public function getBonus(): int
    {
        return $this->getCurrentSkillRank()->getValue() * 2;
    }

    /**
     * @return bool
     */
    public function canSootheRunawayAnimal(): bool
    {
        return $this->getCurrentSkillRank()->getValue() >= 2;
    }
}