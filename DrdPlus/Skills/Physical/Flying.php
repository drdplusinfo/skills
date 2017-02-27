<?php
namespace DrdPlus\Skills\Physical;

use DrdPlus\Codes\Skills\PhysicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Skills\WithBonus;

/**
 * @ORM\Entity()
 */
class Flying extends PhysicalSkill implements WithBonus
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