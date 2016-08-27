<?php
namespace DrdPlus\Skills\Physical;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class FightWithMacesAndClubs extends FightWithWeaponsUsingPhysicalSkill
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