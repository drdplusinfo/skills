<?php
declare(strict_types = 1);

namespace DrdPlus\Skills\Psychical;

use DrdPlus\Codes\Skills\PsychicalSkillCode;
use DrdPlus\Skills\WithBonusToIntelligence;

/**
 * @link https://pph.drdplus.info/#cizi_jazyk
 * @Doctrine\ORM\Mapping\Entity()
 */
class ForeignLanguage extends PsychicalSkill implements WithBonusToIntelligence
{
    public const FOREIGN_LANGUAGE = PsychicalSkillCode::FOREIGN_LANGUAGE;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::FOREIGN_LANGUAGE;
    }

    /**
     * @return int
     */
    public function getBonusToIntelligence(): int
    {
        return 3 * $this->getCurrentSkillRank()->getValue();
    }
}