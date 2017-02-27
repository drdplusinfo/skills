<?php
namespace DrdPlus\Skills\Physical;

use DrdPlus\Codes\Skills\PhysicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Skills\WithBonus;

/**
 * @ORM\Entity()
 */
class Riding extends PhysicalSkill implements WithBonus
{
    const RIDING = PhysicalSkillCode::RIDING;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::RIDING;
    }

    /**
     * @return int
     */
    public function getBonus(): int
    {
        $currentSkillRank = $this->getCurrentSkillRank()->getValue();

        if ($currentSkillRank === 0) {
            return 0;
        }

        return $currentSkillRank * 2 + 2;
    }

}