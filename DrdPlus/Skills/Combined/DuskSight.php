<?php
declare(strict_types = 1);

namespace DrdPlus\Skills\Combined;

use DrdPlus\Codes\Skills\CombinedSkillCode;
use DrdPlus\Lighting\Partials\WithInsufficientLightingBonus;

/**
 * @link https://pph.drdplus.info/#serozrakost
 * @Doctrine\ORM\Mapping\Entity()
 */
class DuskSight extends CombinedSkill implements WithInsufficientLightingBonus
{
    public const DUSK_SIGHT = CombinedSkillCode::DUSK_SIGHT;

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