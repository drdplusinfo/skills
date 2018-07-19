<?php
declare(strict_types = 1);

namespace DrdPlus\Skills\Physical;

use DrdPlus\Codes\Skills\PhysicalSkillCode;
use DrdPlus\Skills\WithBonus;

/**
 * @link https://pph.drdplus.info/#letectvi
 * @Doctrine\ORM\Mapping\Entity()
 */
class Flying extends PhysicalSkill implements WithBonus
{
    public const FLYING = PhysicalSkillCode::FLYING;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::FLYING;
    }

    /**
     * @return int
     */
    public function getBonus(): int
    {
        return $this->getCurrentSkillRank()->getValue() * 2;
    }

    /**
     * @return int
     */
    public function getMalusToFight(): int
    {
        return -9 + 3 * $this->getCurrentSkillRank()->getValue();
    }

}