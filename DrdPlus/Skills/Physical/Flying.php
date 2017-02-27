<?php
namespace DrdPlus\Skills\Physical;

use DrdPlus\Codes\Skills\PhysicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Skills\WithBonusFromSkill;

/**
 * @ORM\Entity()
 */
class Flying extends PhysicalSkill implements WithBonusFromSkill
{
    const FLYING = PhysicalSkillCode::FLYING;

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
    public function getBonusFromSkill(): int
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