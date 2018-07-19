<?php
declare(strict_types = 1);

namespace DrdPlus\Skills\Physical;

use DrdPlus\Codes\Skills\PhysicalSkillCode;
use DrdPlus\Skills\WithBonusToMovementSpeed;

/**
 * @link https://pph.drdplus.info/#plavani
 * @Doctrine\ORM\Mapping\Entity()
 */
class Swimming extends PhysicalSkill implements WithBonusToMovementSpeed
{
    public const SWIMMING = PhysicalSkillCode::SWIMMING;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::SWIMMING;
    }

    /**
     * @return int
     */
    public function getBonusToSwimming(): int
    {
        $currentSkillRankValue = $this->getCurrentSkillRank()->getValue();
        if ($currentSkillRankValue === 0) {
            return 0;
        }

        return $currentSkillRankValue * 2 + 2;
    }

    /**
     * @return int
     */
    public function getBonusToMovementSpeed(): int
    {
        $currentSkillRankValue = $this->getCurrentSkillRank()->getValue();
        if ($currentSkillRankValue === 0) {
            return 0;
        }

        return $currentSkillRankValue + 1;
    }
}