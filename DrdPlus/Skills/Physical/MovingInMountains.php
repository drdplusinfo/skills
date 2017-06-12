<?php
namespace DrdPlus\Skills\Physical;

use DrdPlus\Codes\Skills\PhysicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Skills\WithBonusToMovementSpeed;

/**
 * @link https://pph.drdplus.info/#pohyb_v_horach
 * @ORM\Entity()
 */
class MovingInMountains extends PhysicalSkill implements WithBonusToMovementSpeed
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
    public function getBonusToMovementSpeed(): int
    {
        return $this->getCurrentSkillRank()->getValue();
    }

}