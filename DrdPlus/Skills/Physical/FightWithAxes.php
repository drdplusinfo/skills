<?php
declare(strict_types = 1);

namespace DrdPlus\Skills\Physical;


/**
 * @link https://pph.drdplus.info/#boj_se_zbrani
 * @Doctrine\ORM\Mapping\Entity()
 */
class FightWithAxes extends FightWithWeaponsUsingPhysicalSkill
{
    public const FIGHT_WITH_AXES = 'fight_with_axes';

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::FIGHT_WITH_AXES;
    }

}