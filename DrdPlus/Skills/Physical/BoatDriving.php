<?php
namespace DrdPlus\Skills\Physical;

use DrdPlus\Codes\Skills\PhysicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Skills\WithBonusToMovementSpeed;

/**
 * @link https://pph.drdplus.info/#ovladani_lodky
 */
/**
 * @ORM\Entity()
 */
class BoatDriving extends PhysicalSkill implements WithBonusToMovementSpeed
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
    public function getBonusToMovementSpeed(): int
    {
        return $this->getCurrentSkillRank()->getValue();
    }

}