<?php
namespace DrdPlus\Skills\Physical;

use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Codes\Skills\PhysicalSkillCode;
use DrdPlus\Skills\WithBonusFromSkill;

/**
 * @ORM\Entity()
 */
class ArmorWearing extends PhysicalSkill implements WithBonusFromSkill
{
    const ARMOR_WEARING = PhysicalSkillCode::ARMOR_WEARING;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::ARMOR_WEARING;
    }

    /**
     * @return int
     */
    public function getBonusFromSkill(): int
    {
        return $this->getCurrentSkillRank()->getValue();
    }

}