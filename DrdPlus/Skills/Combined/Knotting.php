<?php
declare(strict_types = 1);

namespace DrdPlus\Skills\Combined;

use DrdPlus\Codes\Skills\CombinedSkillCode;
use DrdPlus\Skills\WithBonusToKnack;

/**
 * @link https://pph.drdplus.info/#uzlovani
 * @Doctrine\ORM\Mapping\Entity()
 */
class Knotting extends CombinedSkill implements WithBonusToKnack
{
    public const KNOTTING = CombinedSkillCode::KNOTTING;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::KNOTTING;
    }

    /**
     * @return int
     */
    public function getBonusToKnack(): int
    {
        return $this->getCurrentSkillRank()->getValue();
    }

}