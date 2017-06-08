<?php
namespace DrdPlus\Skills\Combined;

use DrdPlus\Codes\Skills\CombinedSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Skills\WithBonusToKnack;

/**
 * @ORM\Entity()
 */
class Knotting extends CombinedSkill implements WithBonusToKnack
{
    const KNOTTING = CombinedSkillCode::KNOTTING;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::KNOTTING;
    }

    /**
     * @return int
     */
    public function getBonusToKnack(): int
    {
        return $this->getCurrentSkillRank()->getValue();
    }

}