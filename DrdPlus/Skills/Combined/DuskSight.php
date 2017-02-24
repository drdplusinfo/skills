<?php
namespace DrdPlus\Skills\Combined;

use DrdPlus\Codes\Skills\CombinedSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Lighting\Partials\WithInsufficientLightingBonus;

/**
 * @ORM\Entity()
 */
class DuskSight extends CombinedSkill implements WithInsufficientLightingBonus
{
    const DUSK_SIGHT = CombinedSkillCode::DUSK_SIGHT;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::DUSK_SIGHT;
    }

    /**
     * @return int
     */
    public function getInsufficientLightingBonus(): int
    {
        return $this->getCurrentSkillRank()->getValue(); // equal to skill rank value
    }
}