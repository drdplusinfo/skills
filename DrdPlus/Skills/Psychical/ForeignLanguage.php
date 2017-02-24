<?php
namespace DrdPlus\Skills\Psychical;
use DrdPlus\Codes\Skills\PsychicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity()
 */
class ForeignLanguage extends PsychicalSkill
{
    const FOREIGN_LANGUAGE = PsychicalSkillCode::FOREIGN_LANGUAGE;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::FOREIGN_LANGUAGE;
    }
}