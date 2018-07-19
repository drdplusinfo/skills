<?php
declare(strict_types = 1);

namespace DrdPlus\Skills\Psychical;

use DrdPlus\Codes\Skills\PsychicalSkillCode;
use DrdPlus\Skills\WithBonusToIntelligence;

/**
 * @link https://pph.drdplus.info/#dejeprava
 * @Doctrine\ORM\Mapping\Entity()
 */
class Historiography extends PsychicalSkill implements WithBonusToIntelligence
{
    public const HISTORIOGRAPHY = PsychicalSkillCode::HISTORIOGRAPHY;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::HISTORIOGRAPHY;
    }

    /**
     * @return int
     */
    public function getBonusToIntelligence(): int
    {
        return 3 * $this->getCurrentSkillRank()->getValue();
    }
}