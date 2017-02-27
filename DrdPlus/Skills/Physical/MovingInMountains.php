<?php
namespace DrdPlus\Skills\Physical;

use DrdPlus\Codes\Skills\PhysicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Skills\WithBonus;

/**
 * @ORM\Entity()
 */
class MovingInMountains extends PhysicalSkill implements WithBonus
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
    public function getBonus(): int
    {
        return $this->getCurrentSkillRank()->getValue();
    }

}