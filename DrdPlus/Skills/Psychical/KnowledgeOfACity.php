<?php
namespace DrdPlus\Skills\Psychical;

use DrdPlus\Codes\Skills\PsychicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Skills\WithBonusToIntelligence;

/**
 * @ORM\Entity()
 */
class KnowledgeOfACity extends PsychicalSkill implements WithBonusToIntelligence
{
    const KNOWLEDGE_OF_A_CITY = PsychicalSkillCode::KNOWLEDGE_OF_A_CITY;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::KNOWLEDGE_OF_A_CITY;
    }

    /**
     * @return int
     */
    public function getBonusToIntelligence(): int
    {
        return 3 * $this->getCurrentSkillRank()->getValue();
    }

}