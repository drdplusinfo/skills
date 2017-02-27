<?php
namespace DrdPlus\Skills\Physical;

use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Codes\Skills\PhysicalSkillCode;
use DrdPlus\Skills\WithBonusFromSkill;

/**
 * @ORM\Entity()
 */
class FastMarsh extends PhysicalSkill implements WithBonusFromSkill
{
    const FAST_MARSH = PhysicalSkillCode::FAST_MARSH;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::FAST_MARSH;
    }

    /**
     * @return int
     */
    public function getBonusFromSkill(): int
    {
        return $this->getCurrentSkillRank()->getValue();
    }

}