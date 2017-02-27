<?php
namespace DrdPlus\Skills\Physical;

use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Codes\Skills\PhysicalSkillCode;
use DrdPlus\Skills\WithBonusFromSkill;

/**
 * @ORM\Entity()
 */
class Blacksmithing extends PhysicalSkill implements WithBonusFromSkill
{
    const BLACKSMITHING = PhysicalSkillCode::BLACKSMITHING;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::BLACKSMITHING;
    }

    /**
     * @return int
     */
    public function getBonusFromSkill(): int
    {
        return $this->getCurrentSkillRank()->getValue() * 2;
    }

}