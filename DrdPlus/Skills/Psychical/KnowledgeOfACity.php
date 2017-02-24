<?php
namespace DrdPlus\Skills\Psychical;
use DrdPlus\Codes\Skills\PsychicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity()
 */
class KnowledgeOfACity extends PsychicalSkill
{
    const KNOWLEDGE_OF_A_CITY = PsychicalSkillCode::KNOWLEDGE_OF_A_CITY;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::KNOWLEDGE_OF_A_CITY;
    }
}