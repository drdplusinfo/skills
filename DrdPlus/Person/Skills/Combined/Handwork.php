<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\SkillCodes;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Handwork extends PersonCombinedSkill
{
    const HANDWORK = SkillCodes::HANDWORK;

    /**
     * @return string
     */
    public function getName()
    {
        return self::HANDWORK;
    }

}
