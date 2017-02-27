<?php
namespace DrdPlus\Skills\Physical;

use DrdPlus\Codes\Skills\PhysicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Skills\WithBonusFromSkill;

/**
 * @ORM\Entity()
 */
class MovingInMountains extends PhysicalSkill implements WithBonusFromSkill
{
    const MOVING_IN_MOUNTAINS = PhysicalSkillCode::MOVING_IN_MOUNTAINS;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::MOVING_IN_MOUNTAINS;
    }

    /**
     * @return int
     */
    public function getBonusFromSkill(): int
    {
        return $this->getCurrentSkillRank()->getValue();
    }

}