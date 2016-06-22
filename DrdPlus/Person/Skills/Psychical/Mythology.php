<?php
namespace DrdPlus\Person\Skills\Psychical;
use DrdPlus\Codes\PsychicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity()
 */
class Mythology extends PersonPsychicalSkill
{
    const MYTHOLOGY = PsychicalSkillCode::MYTHOLOGY;

    /**
     * @return string
     */
    public function getName()
    {
        return self::MYTHOLOGY;
    }
}
