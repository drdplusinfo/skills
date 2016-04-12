<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\SkillCodes;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class HuntingAndFishing extends PersonCombinedSkill
{
    const HUNTING_AND_FISHING = SkillCodes::HUNTING_AND_FISHING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::HUNTING_AND_FISHING;
    }
}
