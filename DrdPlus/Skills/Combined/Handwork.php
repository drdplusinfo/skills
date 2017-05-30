<?php
namespace DrdPlus\Skills\Combined;

use DrdPlus\Codes\Skills\CombinedSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Skills\WithBonusToKnack;

/**
 * @ORM\Entity()
 */
class Handwork extends CombinedSkill implements WithBonusToKnack
{
    const HANDWORK = CombinedSkillCode::HANDWORK;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::HANDWORK;
    }

    /**
     * @return int
     */
    public function getBonusToKnack(): int
    {
        return 2 * $this->getCurrentSkillRank()->getValue();
    }

}