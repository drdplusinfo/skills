<?php
namespace DrdPlus\Person\Skills\Psychical;
use DrdPlus\Codes\PsychicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity()
 */
class Technology extends PersonPsychicalSkill
{
    const TECHNOLOGY = PsychicalSkillCode::TECHNOLOGY;

    /**
     * @return string
     */
    public function getName()
    {
        return self::TECHNOLOGY;
    }
}
