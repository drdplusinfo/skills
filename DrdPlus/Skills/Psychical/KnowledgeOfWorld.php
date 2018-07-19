<?php
declare(strict_types = 1);

namespace DrdPlus\Skills\Psychical;

use DrdPlus\Codes\Skills\PsychicalSkillCode;
use DrdPlus\Skills\WithBonus;

/**
 * @link https://pph.drdplus.info/#znalost_sveta
 * @Doctrine\ORM\Mapping\Entity()
 */
class KnowledgeOfWorld extends PsychicalSkill implements WithBonus
{
    public const KNOWLEDGE_OF_WORLD = PsychicalSkillCode::KNOWLEDGE_OF_WORLD;

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