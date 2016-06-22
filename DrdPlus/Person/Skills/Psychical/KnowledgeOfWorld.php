<?php
namespace DrdPlus\Person\Skills\Psychical;
use DrdPlus\Codes\PsychicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity()
 */
class KnowledgeOfWorld extends PersonPsychicalSkill
{
    const KNOWLEDGE_OF_WORLD = PsychicalSkillCode::KNOWLEDGE_OF_WORLD;

    /**
     * @return string
     */
    public function getName()
    {
        return self::KNOWLEDGE_OF_WORLD;
    }
}
