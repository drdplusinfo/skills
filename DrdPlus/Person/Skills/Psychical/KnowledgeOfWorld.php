<?php
namespace DrdPlus\Person\Skills\Psychical;
use DrdPlus\Codes\SkillCodes;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class KnowledgeOfWorld extends PersonPsychicalSkill
{
    const KNOWLEDGE_OF_WORLD = SkillCodes::KNOWLEDGE_OF_WORLD;

    /**
     * @return string
     */
    public function getName()
    {
        return self::KNOWLEDGE_OF_WORLD;
    }
}
