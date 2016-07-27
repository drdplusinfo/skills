<?php
namespace DrdPlus\Person\Skills\Physical;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class FightWithAxes extends FightWithWeaponsUsingPhysicalSkill
{
    const FIGHT_WITH_AXES = 'fight_with_axes';

    /**
     * @return string
     */
    public function getName()
    {
        return self::FIGHT_WITH_AXES;
    }

}