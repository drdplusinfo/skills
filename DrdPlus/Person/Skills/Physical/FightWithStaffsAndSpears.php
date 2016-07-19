<?php
namespace DrdPlus\Person\Skills\Physical;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class FightWithStaffsAndSpears extends FightWithWeapon
{
    const FIGHT_WITH_STAFFS_AND_SPEARS = 'fight_with_staffs_and_spears';

    /**
     * @return string
     */
    public function getName()
    {
        return self::FIGHT_WITH_STAFFS_AND_SPEARS;
    }

}