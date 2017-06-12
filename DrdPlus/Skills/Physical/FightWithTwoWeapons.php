<?php
namespace DrdPlus\Skills\Physical;

use Doctrine\ORM\Mapping as ORM;

/**
 * @link https://pph.drdplus.info/#boj_se_zbrani
 * @ORM\Entity()
 */
class FightWithTwoWeapons extends FightWithWeaponsUsingPhysicalSkill
{
    const FIGHT_WITH_TWO_WEAPONS = 'fight_with_two_weapons';

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::FIGHT_WITH_TWO_WEAPONS;
    }

}