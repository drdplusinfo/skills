<?php
namespace DrdPlus\Skills\Combined;

use DrdPlus\Codes\Skills\CombinedSkillCode;
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
    public function getName(): string
    {
        return self::FIRST_AID;
    }
}