<?php
namespace DrdPlus\Person\Skills\Psychical;
use DrdPlus\Codes\PsychicalSkillCode;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity()
 */
class Theology extends PersonPsychicalSkill
{
    const THEOLOGY = PsychicalSkillCode::THEOLOGY;

    /**
     * @return string
     */
    public function getName()
    {
        return self::THEOLOGY;
    }
}
