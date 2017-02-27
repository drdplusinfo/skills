<?php
namespace DrdPlus\Skills\Physical;
use DrdPlus\Codes\Skills\PhysicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Skills\WithBonus;

/**
 * @ORM\Entity()
 */
class Sailing extends PhysicalSkill implements WithBonus
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
    public function getBonus(): int
    {
        return $this->getCurrentSkillRank()->getValue();
    }

}