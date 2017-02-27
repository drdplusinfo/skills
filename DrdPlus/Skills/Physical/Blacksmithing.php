<?php
namespace DrdPlus\Skills\Physical;

use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Codes\Skills\PhysicalSkillCode;
use DrdPlus\Skills\WithBonus;

/**
 * @ORM\Entity()
 */
class Blacksmithing extends PhysicalSkill implements WithBonus
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
    public function getBonus(): int
    {
        return $this->getCurrentSkillRank()->getValue() * 2;
    }

}