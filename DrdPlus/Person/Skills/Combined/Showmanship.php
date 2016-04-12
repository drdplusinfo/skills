<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\SkillCodes;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Showmanship extends PersonCombinedSkill
{
    const SHOWMANSHIP = SkillCodes::SHOWMANSHIP;

    /**
     * @return string
     */
    public function getName()
    {
        return self::SHOWMANSHIP;
    }
}
