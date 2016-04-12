<?php
namespace DrdPlus\Person\Skills\Psychical;
use DrdPlus\Codes\SkillCodes;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity()
 */
class ForeignLanguage extends PersonPsychicalSkill
{
    const FOREIGN_LANGUAGE = SkillCodes::FOREIGN_LANGUAGE;

    /**
     * @return string
     */
    public function getName()
    {
        return self::FOREIGN_LANGUAGE;
    }
}
