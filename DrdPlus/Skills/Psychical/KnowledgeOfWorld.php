<?php
namespace DrdPlus\Skills\Psychical;

use DrdPlus\Codes\Skills\PsychicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Skills\WithBonus;

/**
 * @ORM\Entity()
 */
class KnowledgeOfWorld extends PsychicalSkill implements WithBonus
{
    const KNOWLEDGE_OF_WORLD = PsychicalSkillCode::KNOWLEDGE_OF_WORLD;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::KNOWLEDGE_OF_WORLD;
    }

    /**
     * @return int
     */
    public function getBonus(): int
    {
        return 2 * $this->getCurrentSkillRank()->getValue();
    }

}