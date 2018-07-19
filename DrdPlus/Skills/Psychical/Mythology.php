<?php
declare(strict_types = 1);

namespace DrdPlus\Skills\Psychical;

use DrdPlus\Codes\Skills\PsychicalSkillCode;
use DrdPlus\Skills\WithBonusToIntelligence;

/**
 * @link https://pph.drdplus.info/#bajeslovi
 * @Doctrine\ORM\Mapping\Entity()
 */
class Mythology extends PsychicalSkill implements WithBonusToIntelligence
{
    public const MYTHOLOGY = PsychicalSkillCode::MYTHOLOGY;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::MYTHOLOGY;
    }

    /**
     * @return int
     */
    public function getBonusToIntelligence(): int
    {
        return 3 * $this->getCurrentSkillRank()->getValue();
    }
}