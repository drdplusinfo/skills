<?php
declare(strict_types = 1);

namespace DrdPlus\Skills\Psychical;

use DrdPlus\Codes\Skills\PsychicalSkillCode;
use DrdPlus\Skills\WithBonusToCharisma;

/**
 * @link https://pph.drdplus.info/#spolecenska_etiketa
 * @Doctrine\ORM\Mapping\Entity()
 */
class SocialEtiquette extends PsychicalSkill implements WithBonusToCharisma
{
    public const SOCIAL_ETIQUETTE = PsychicalSkillCode::SOCIAL_ETIQUETTE;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::SOCIAL_ETIQUETTE;
    }

    /**
     * @return int
     */
    public function getBonusToCharisma(): int
    {
        return $this->getCurrentSkillRank()->getValue();
    }
}