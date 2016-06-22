<?php
namespace DrdPlus\Person\Skills\Psychical;

use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Codes\PsychicalSkillCode;

/**
 * @ORM\Entity()
 */
class Astronomy extends PersonPsychicalSkill
{
    const ASTRONOMY = PsychicalSkillCode::ASTRONOMY;

    /**
     * @return string
     */
    public function getName()
    {
        return self::ASTRONOMY;
    }
}
