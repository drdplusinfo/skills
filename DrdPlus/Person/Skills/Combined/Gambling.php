<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\CombinedSkillCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Gambling extends PersonCombinedSkill
{
    const GAMBLING = CombinedSkillCode::GAMBLING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::GAMBLING;
    }
}
