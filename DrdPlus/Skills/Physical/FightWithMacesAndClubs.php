<?php
namespace DrdPlus\Skills\Physical;

use Doctrine\ORM\Mapping as ORM;

/**
 * @link https://pph.drdplus.info/#boj_se_zbrani
 * @ORM\Entity()
 */
class FightWithMacesAndClubs extends FightWithWeaponsUsingPhysicalSkill
{
    const FIGHT_WITH_MACES_AND_CLUBS = 'fight_with_maces_and_clubs';

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::FIGHT_WITH_MACES_AND_CLUBS;
    }

}