<?php
namespace DrdPlus\Skills\Psychical;

use DrdPlus\Codes\Skills\PsychicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Skills\WithBonusToIntelligence;
use DrdPlus\Skills\WithBonusToSenses;

/**
 * @ORM\Entity()
 */
class Technology extends PsychicalSkill implements WithBonusToIntelligence, WithBonusToSenses
{
    const TECHNOLOGY = PsychicalSkillCode::TECHNOLOGY;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::TECHNOLOGY;
    }

    /**
     * @return int
     */
    public function getBonusToIntelligence(): int
    {
        return 3 * $this->getCurrentSkillRank()->getValue();
    }

    /**
     * @return int
     */
    public function getBonusToSenses(): int
    {
        return $this->getCurrentSkillRank()->getValue();
    }

}