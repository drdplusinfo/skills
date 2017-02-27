<?php
namespace DrdPlus\Skills\Physical;

use DrdPlus\Codes\Skills\PhysicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Skills\WithBonusFromSkill;

/**
 * @ORM\Entity()
 */
class BoatDriving extends PhysicalSkill implements WithBonusFromSkill
{
    const BOAT_DRIVING = PhysicalSkillCode::BOAT_DRIVING;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::BOAT_DRIVING;
    }

    /**
     * @return int
     */
    public function getBonusFromSkill(): int
    {
        return $this->getCurrentSkillRank()->getValue();
    }

}