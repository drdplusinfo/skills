<?php
namespace DrdPlus\Person\Skills\Combined;

use DrdPlus\Codes\CombinedSkillCode;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class HuntingAndFishing extends PersonCombinedSkill
{
    const HUNTING_AND_FISHING = CombinedSkillCode::HUNTING_AND_FISHING;

    /**
     * @return string
     */
    public function getName()
    {
        return self::HUNTING_AND_FISHING;
    }
}
