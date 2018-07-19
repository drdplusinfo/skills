<?php
declare(strict_types = 1);

namespace DrdPlus\Skills\Physical;

use DrdPlus\Codes\Skills\PhysicalSkillCode;
use DrdPlus\Skills\WithBonusToMovementSpeed;

/**
 * @link https://pph.drdplus.info/#pohyb_v_horach
 * @Doctrine\ORM\Mapping\Entity()
 */
class MovingInMountains extends PhysicalSkill implements WithBonusToMovementSpeed
{
    public const MOVING_IN_MOUNTAINS = PhysicalSkillCode::MOVING_IN_MOUNTAINS;

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