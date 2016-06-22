<?php
namespace DrdPlus\Person\Skills\Psychical;
use DrdPlus\Codes\PsychicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity()
 */
class KnowledgeOfACity extends PersonPsychicalSkill
{
    const KNOWLEDGE_OF_A_CITY = PsychicalSkillCode::KNOWLEDGE_OF_A_CITY;

    /**
     * @return string
     */
    public function getName()
    {
        return self::KNOWLEDGE_OF_A_CITY;
    }
}
