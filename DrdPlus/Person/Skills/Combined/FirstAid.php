<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\CombinedSkillCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class FirstAid extends PersonCombinedSkill
{
    const FIRST_AID = CombinedSkillCode::FIRST_AID;

    /**
     * @return string
     */
    public function getName()
    {
        return self::FIRST_AID;
    }
}
