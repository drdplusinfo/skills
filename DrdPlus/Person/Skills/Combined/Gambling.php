<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\SkillCodes;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Gambling extends PersonCombinedSkill
{
    const GAMBLING = SkillCodes::GAMBLING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::GAMBLING;
    }
}
