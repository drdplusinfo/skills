<?php
namespace DrdPlus\Skills\Physical;

use DrdPlus\Codes\Skills\PhysicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Skills\WithBonus;

/**
 * @ORM\Entity()
 */
class ClimbingAndHillwalking extends PhysicalSkill implements WithBonus
{
    const CLIMBING_AND_HILLWALKING = PhysicalSkillCode::CLIMBING_AND_HILLWALKING;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::CLIMBING_AND_HILLWALKING;
    }

    /**
     * @return int
     */
    public function getBonus(): int
    {
        return $this->getCurrentSkillRank()->getValue();
    }
}