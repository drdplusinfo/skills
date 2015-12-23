<?php
namespace DrdPlus\Person\Skills\Psychical;
use DrdPlus\Codes\SkillCodes;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class KnowledgeOfACity extends PersonPsychicalSkill
{
    const KNOWLEDGE_OF_A_CITY = SkillCodes::KNOWLEDGE_OF_A_CITY;

    /**
     * @return string
     */
    public function getName()
    {
        return self::KNOWLEDGE_OF_A_CITY;
    }
}
