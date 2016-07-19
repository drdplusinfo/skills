<?php
namespace DrdPlus\Person\Skills\Physical;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class FightWithThrowingWeapons extends FightWithWeapon
{
    const FIGHT_WITH_THROWING_WEAPONS = 'fight_with_throwing_weapons';

    /**
     * @return string
     */
    public function getName()
    {
        return self::FIGHT_WITH_THROWING_WEAPONS;
    }

}