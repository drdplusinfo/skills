<?php
namespace DrdPlus\Person\Skills\Physical;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class FightWithMacesAndClubs extends FightWithMeleeWeapon
{
    const FIGHT_WITH_MACES_AND_CLUBS = 'fight_with_maces_and_clubs';

    /**
     * @return string
     */
    public function getName()
    {
        return self::FIGHT_WITH_MACES_AND_CLUBS;
    }

}