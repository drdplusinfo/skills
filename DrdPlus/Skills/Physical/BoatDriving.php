<?php
namespace DrdPlus\Skills\Physical;

use DrdPlus\Codes\Skills\PhysicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Skills\WithBonus;

/**
 * @ORM\Entity()
 */
class BoatDriving extends PhysicalSkill implements WithBonus
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
    public function getBonus(): int
    {
        return $this->getCurrentSkillRank()->getValue();
    }

}