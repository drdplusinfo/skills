<?php
namespace DrdPlus\Skills\Psychical;

use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Codes\PsychicalSkillCode;

/**
 * @ORM\Entity()
 */
class Astronomy extends PsychicalSkill
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
