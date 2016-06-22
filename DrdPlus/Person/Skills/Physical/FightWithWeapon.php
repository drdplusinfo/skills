<?php
namespace DrdPlus\Person\Skills\Physical;

use Doctrine\ORM\Mapping as ORM;
use DrdPlus\Codes\PhysicalSkillCode;

/**
 * @ORM\Entity()
 */
class FightWithWeapon extends PersonPhysicalSkill
{
    const FIGHT_WITH_WEAPON = PhysicalSkillCode::FIGHT_WITH_WEAPON;

    /**
     * @return string
     */
    public function getName()
    {
        return self::FIGHT_WITH_WEAPON;
    }
}
