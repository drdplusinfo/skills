<?php
namespace DrdPlus\Person\Skills\Physical;

use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Codes\SkillCodes;

/**
 * @ORM\Entity()
 */
class FightWithWeapon extends PersonPhysicalSkill
{
    const FIGHT_WITH_WEAPON = SkillCodes::FIGHT_WITH_WEAPON;

    /**
     * @return string
     */
    public function getName()
    {
        return self::FIGHT_WITH_WEAPON;
    }
}
