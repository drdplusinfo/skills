<?php
namespace DrdPlus\Skills\Physical;

use Doctrine\ORM\Mapping as ORM;

/**
 * @link https://pph.drdplus.info/#boj_se_zbrani
 * @ORM\Entity()
 */
class FightWithStaffsAndSpears extends FightWithWeaponsUsingPhysicalSkill
{
    const FIGHT_WITH_STAFFS_AND_SPEARS = 'fight_with_staffs_and_spears';

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::FIGHT_WITH_STAFFS_AND_SPEARS;
    }

}