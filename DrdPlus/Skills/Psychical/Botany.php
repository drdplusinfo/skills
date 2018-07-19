<?php
declare(strict_types = 1);

namespace DrdPlus\Skills\Psychical;

use DrdPlus\Codes\Skills\PsychicalSkillCode;
use DrdPlus\Skills\WithBonusToIntelligence;

/**
 * @link https://pph.drdplus.info/#botanika
 * @Doctrine\ORM\Mapping\Entity()
 */
class Botany extends PsychicalSkill implements WithBonusToIntelligence
{
    public const BOTANY = PsychicalSkillCode::BOTANY;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::BOTANY;
    }

    /**
     * @return int
     */
    public function getBonusToIntelligence(): int
    {
        return 3 * $this->getCurrentSkillRank()->getValue();
    }
}