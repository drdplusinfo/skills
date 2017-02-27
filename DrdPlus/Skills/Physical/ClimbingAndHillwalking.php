<?php
namespace DrdPlus\Skills\Physical;

use DrdPlus\Codes\Skills\PhysicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Skills\WithBonusFromSkill;

/**
 * @ORM\Entity()
 */
class ClimbingAndHillwalking extends PhysicalSkill implements WithBonusFromSkill
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
    public function getBonusFromSkill(): int
    {
        return $this->getCurrentSkillRank()->getValue();
    }
}