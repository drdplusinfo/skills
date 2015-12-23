<?php
namespace DrdPlus\Person\Skills\Psychical;

use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Codes\SkillCodes;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class Astronomy extends PersonPsychicalSkill
{
    const ASTRONOMY = SkillCodes::ASTRONOMY;

    /**
     * @return string
     */
    public function getName()
    {
        return self::ASTRONOMY;
    }
}
