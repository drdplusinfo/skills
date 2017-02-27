<?php
namespace DrdPlus\Skills\Physical;

use DrdPlus\Codes\Skills\PhysicalSkillCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Swimming extends PhysicalSkill
{
    const SWIMMING = PhysicalSkillCode::SWIMMING;

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
    public function getBonusToSpeed(): int
    {
        $currentSkillRankValue = $this->getCurrentSkillRank()->getValue();
        if ($currentSkillRankValue === 0) {
            return 0;
        }

        return $currentSkillRankValue + 1;
    }
}