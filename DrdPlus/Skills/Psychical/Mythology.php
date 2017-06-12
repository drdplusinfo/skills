<?php
namespace DrdPlus\Skills\Psychical;

use DrdPlus\Codes\Skills\PsychicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Skills\WithBonusToIntelligence;

/**
 * @link https://pph.drdplus.info/#bajeslovi
 * @ORM\Entity()
 */
class Mythology extends PsychicalSkill implements WithBonusToIntelligence
{
    const MYTHOLOGY = PsychicalSkillCode::MYTHOLOGY;

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