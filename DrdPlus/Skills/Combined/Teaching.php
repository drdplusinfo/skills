<?php
declare(strict_types = 1);

namespace DrdPlus\Skills\Combined;

use DrdPlus\Codes\Skills\CombinedSkillCode;
use DrdPlus\Skills\WithBonus;

/**
 * @link https://pph.drdplus.info/#vyucovani
 * @Doctrine\ORM\Mapping\Entity()
 */
class Teaching extends CombinedSkill implements WithBonus
{
    public const TEACHING = CombinedSkillCode::TEACHING;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::TEACHING;
    }

    /**
     * @return int
     */
    public function getBonus(): int
    {
        return $this->getCurrentSkillRank()->getValue() * 2;
    }

}