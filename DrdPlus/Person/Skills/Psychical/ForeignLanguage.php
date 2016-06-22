<?php
namespace DrdPlus\Person\Skills\Psychical;
use DrdPlus\Codes\PsychicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity()
 */
class ForeignLanguage extends PersonPsychicalSkill
{
    const FOREIGN_LANGUAGE = PsychicalSkillCode::FOREIGN_LANGUAGE;

    /**
     * @return string
     */
    public function getName()
    {
        return self::FOREIGN_LANGUAGE;
    }
}
