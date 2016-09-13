<?php
namespace DrdPlus\Skills\Physical;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class FightWithShields extends FightWithWeaponsUsingPhysicalSkill
{
    const FIGHT_WITH_SHIELDS = 'fight_with_shields';

    /**
     * @return string
     */
    public function getName()
    {
        return self::FIGHT_WITH_SHIELDS;
    }

}