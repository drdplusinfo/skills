<?php
declare(strict_types = 1);

namespace DrdPlus\Skills\Combined;

use DrdPlus\Codes\Skills\CombinedSkillCode;
use DrdPlus\Skills\WithBonusToKnack;

/**
 * @link https://pph.drdplus.info/#velke_rucni_prace
 * @Doctrine\ORM\Mapping\Entity()
 */
class BigHandwork extends CombinedSkill implements WithBonusToKnack
{
    public const BIG_HANDWORK = CombinedSkillCode::BIG_HANDWORK;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::BIG_HANDWORK;
    }

    /**
     * @return int
     */
    public function getBonusToKnack(): int
    {
        return 2 * $this->getCurrentSkillRank()->getValue();
    }

}