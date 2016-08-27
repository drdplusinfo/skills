<?php
namespace DrdPlus\Skills\Combined;

use DrdPlus\Codes\CombinedSkillCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class FirstAid extends CombinedSkill
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
