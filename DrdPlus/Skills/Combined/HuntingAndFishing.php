<?php
namespace DrdPlus\Skills\Combined;

use DrdPlus\Codes\Skills\CombinedSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\HuntingAndFishing\HuntingAndFishingSkillBonus;
use DrdPlus\Skills\WithBonusFromSkill;

/**
 * @ORM\Entity()
 */
class HuntingAndFishing extends CombinedSkill implements WithBonusFromSkill, HuntingAndFishingSkillBonus
{
    const HUNTING_AND_FISHING = CombinedSkillCode::HUNTING_AND_FISHING;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::HUNTING_AND_FISHING;
    }

    /**
     * @return int
     */
    public function getBonusFromSkill(): int
    {
        return $this->getCurrentSkillRank()->getValue() * 2;
    }
}