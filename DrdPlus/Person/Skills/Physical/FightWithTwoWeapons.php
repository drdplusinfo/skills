<?php
namespace DrdPlus\Person\Skills\Physical;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class FightWithTwoWeapons extends FightWithWeaponUsingPhysicalSkill
{
    const FIGHT_WITH_TWO_WEAPONS = 'fight_with_two_weapons';

    /**
     * @return string
     */
    public function getName()
    {
        return self::FIGHT_WITH_TWO_WEAPONS;
    }

}