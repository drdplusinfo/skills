<?php
namespace DrdPlus\Person\Skills\Psychical;
use DrdPlus\Codes\PsychicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity()
 */
class Botany extends PersonPsychicalSkill
{
    const BOTANY = PsychicalSkillCode::BOTANY;

    /**
     * @return string
     */
    public function getName()
    {
        return self::BOTANY;
    }
}
