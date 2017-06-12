<?php
namespace DrdPlus\Skills\Combined;

use DrdPlus\Codes\Skills\CombinedSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\HuntingAndFishing\WithBonusFromHuntingAndFishingSkill;
use DrdPlus\Skills\WithBonus;

/**
 * @link https://pph.drdplus.info/#lov_a_rybolov
 * @ORM\Entity()
 */
class HuntingAndFishing extends CombinedSkill implements WithBonus, WithBonusFromHuntingAndFishingSkill
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
    public function getBonus(): int
    {
        return 2 * $this->getCurrentSkillRank()->getValue();
    }

    /**
     * @return int
     */
    public function getBonusFromSkill(): int
    {
        return $this->getBonus();
    }

}