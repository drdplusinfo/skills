<?php
namespace DrdPlus\Skills\Physical;

use Doctrine\ORM\Mapping as ORM;

/**
 * @link https://pph.drdplus.info/#boj_se_zbrani
 * @ORM\Entity()
 */
class FightWithShields extends FightWithWeaponsUsingPhysicalSkill
{
    const FIGHT_WITH_SHIELDS = 'fight_with_shields';

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::FIGHT_WITH_SHIELDS;
    }

}