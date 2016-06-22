<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\CombinedSkillCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Showmanship extends PersonCombinedSkill
{
    const SHOWMANSHIP = CombinedSkillCode::SHOWMANSHIP;

    /**
     * @return string
     */
    public function getName()
    {
        return self::SHOWMANSHIP;
    }
}
