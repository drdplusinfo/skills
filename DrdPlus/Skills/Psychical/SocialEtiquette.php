<?php
namespace DrdPlus\Skills\Psychical;

use DrdPlus\Codes\Skills\PsychicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Skills\WithBonusToCharisma;

/**
 * @ORM\Entity()
 */
class SocialEtiquette extends PsychicalSkill implements WithBonusToCharisma
{
    const SOCIAL_ETIQUETTE = PsychicalSkillCode::SOCIAL_ETIQUETTE;

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