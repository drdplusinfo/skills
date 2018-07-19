<?php
declare(strict_types = 1);

namespace DrdPlus\Skills\Combined;

use DrdPlus\Codes\Skills\CombinedSkillCode;
use DrdPlus\Skills\WithBonusToIntelligence;
use DrdPlus\Skills\WithBonusToSenses;

/**
 * @link https://pph.drdplus.info/#bylinkarstvi
 * @Doctrine\ORM\Mapping\Entity()
 */
class Herbalism extends CombinedSkill implements WithBonusToIntelligence, WithBonusToSenses
{
    public const HERBALISM = CombinedSkillCode::HERBALISM;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::HERBALISM;
    }

    /**
     * @return int
     */
    public function getBonusToIntelligence(): int
    {
        return 3 * $this->getCurrentSkillRank()->getValue();
    }

    /**
     * @return int
     */
    public function getBonusToSenses(): int
    {
        $currentSkillRankValue = $this->getCurrentSkillRank()->getValue();
        if ($currentSkillRankValue === 0) {
            return 0;
        }

        return 3 * $currentSkillRankValue + 3;
    }

}