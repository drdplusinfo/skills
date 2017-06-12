<?php
namespace DrdPlus\Skills\Physical;

use DrdPlus\Codes\Skills\PhysicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Skills\WithBonusToMovementSpeed;

/**
 * @link https://pph.drdplus.info/#pohyb_v_lese
 * @ORM\Entity()
 */
class ForestMoving extends PhysicalSkill implements WithBonusToMovementSpeed
{
    const FOREST_MOVING = PhysicalSkillCode::FOREST_MOVING;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::FOREST_MOVING;
    }

    /**
     * @return int
     */
    public function getBonusToMovementSpeed(): int
    {
        return $this->getCurrentSkillRank()->getValue();
    }

}