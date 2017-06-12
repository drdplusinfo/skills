<?php
namespace DrdPlus\Skills\Physical;

use DrdPlus\Codes\Skills\PhysicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Skills\WithBonusToMovementSpeed;

/**
 * @link https://pph.drdplus.info/#namornictvi
 * @ORM\Entity()
 */
class Sailing extends PhysicalSkill implements WithBonusToMovementSpeed
{
    const SAILING = PhysicalSkillCode::SAILING;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::SAILING;
    }

    /**
     * @return int
     */
    public function getBonusToMovementSpeed(): int
    {
        return $this->getCurrentSkillRank()->getValue();
    }

}